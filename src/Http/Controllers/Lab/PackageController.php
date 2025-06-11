<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\Package;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\PackageTest;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\PackageRequest;

class PackageController extends Controller
{
   public function __construct()
    {
        $this->middleware('checkPermission:packages.index')->only('index');
        $this->middleware('checkPermission:packages.create')->only('create', 'store');
        $this->middleware('checkPermission:packages.show')->only('show');
        $this->middleware('checkPermission:packages.edit')->only('edit', 'update');
        $this->middleware('checkPermission:packages.destroy')->only('destroy');
    }

    // public function index()
    // {
    //     $packages = Package::with(['testCategory:id,name'])->orderBy('created_at', 'desc')->paginate(100);

    //     $user = Auth::user();
    //     $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
    //     return Inertia::render('Lab/Packages/Index', [
    //         'packages' => $packages,
    //         'role' => $role,
    //     ]);
    // }
    public function index()
    {

        $query = Package::with(['testCategory:id,name']);
        $query = $query->orderByRaw("status = 'inactive' ASC, name ASC");
        $packages = $query->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)
            ->whereIn('role_id', [1, 7])
            ->first();
        return Inertia::render('Lab/Packages/Index', [
            'packages' => $packages,
            'role' => $role,
        ]);
    }

    public function create()
    {
        // $lab_tests = LabTest::select('id', 'name')->orderBy('name')->get();
        $lab_tests = SubPackage::select('id', 'name')->orderBy('name')->get();
        $test_categories = TestCategory::select('id', 'name')->orderBy(column: 'name')->get();
        return Inertia::render('Lab/Packages/Create', [
            'lab_tests' => $lab_tests,
            'test_categories' => $test_categories
        ]);
    }

    public function store(PackageRequest $request)
    {
        DB::beginTransaction();

        $package = new Package();
        $formData = $request->only($package->getFillable());

        $package = Package::create($formData);

        if (!empty($request->lab_tests) && is_array($request->lab_tests)) {
            foreach ($request->lab_tests as $key => $lab_tests) {
                if (!empty($lab_tests)) {
                    PackageTest::create([
                        'package_id' => $package->id,
                        'lab_test_id' => $lab_tests,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('packages.index');
    }


    public function edit(Package $package)
    {
        if (isset($package->packageTests)) {
            $package->lab_tests = $package->packageTests->pluck('lab_test_id');
        }
        $selectedLabTests = null;
        if (isset($package->lab_tests)) {
            $selectedLabTests = SubPackage::whereIn('id', $package->lab_tests)->select('id', 'name')->get();
        }

        $lab_tests = SubPackage::select('id', 'name')->orderBy('name')->get();
        $test_categories = TestCategory::select('id', 'name')->orderBy(column: 'name')->get();

        return Inertia::render('Lab/Packages/Create', [
            'package' => $package,
            'selectedLabTests' => $selectedLabTests,
            'lab_tests' => $lab_tests,
            'test_categories' => $test_categories,
        ]);
    }


    public function update(PackageRequest $request, Package $package)
    {
        DB::beginTransaction();

        $formData = $request->only($package->getFillable());
        $package->update($formData);

        if (!empty($request->lab_tests) && is_array($request->lab_tests)) {
            PackageTest::where('package_id', $package->id)->delete();
            foreach ($request->lab_tests as $key => $lab_tests) {
                if (!empty($lab_tests)) {
                    PackageTest::create([
                        'package_id' => $package->id,
                        'lab_test_id' => $lab_tests,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('packages.index')->with('message', 'Package updated successfully.');
    }


    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
