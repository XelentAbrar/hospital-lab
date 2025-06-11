<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\SubPackageTest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\SubPackageRequest;

class SubPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:sub-packages.index')->only('index');
        $this->middleware('checkPermission:sub-packages.create')->only('create', 'store');
        $this->middleware('checkPermission:sub-packages.show')->only('show');
        $this->middleware('checkPermission:sub-packages.edit')->only('edit', 'update');
        $this->middleware('checkPermission:sub-packages.destroy')->only('destroy');
    }

    // public function index()
    // {
    //     $packages = SubPackage::orderBy('created_at', 'desc')->paginate(100);
    //     $user = Auth::user();
    //     $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
    //     return Inertia::render('Lab/SubPackages/Index', [
    //         'packages' => $packages,
    //         'role' => $role,
    //     ]);
    // }
    public function index()
    {
        $query = SubPackage::orderByRaw("status = 'inactive' ASC, name ASC");
        $packages = $query->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)
            ->whereIn('role_id', [1, 7])
            ->first();

        return Inertia::render('Lab/SubPackages/Index', [
            'packages' => $packages,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $lab_tests = LabTest::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Lab/SubPackages/Create', [
            'lab_tests' => $lab_tests
        ]);
    }

    public function store(SubPackageRequest $request)
    {
        DB::beginTransaction();

        $package = new SubPackage();
        $formData = $request->only($package->getFillable());

        $package = SubPackage::create($formData);

        if (!empty($request->lab_tests) && is_array($request->lab_tests)) {
            foreach ($request->lab_tests as $key => $lab_tests) {
                if (!empty($lab_tests)) {
                    SubPackageTest::create([
                        'sub_package_id' => $package->id,
                        'lab_test_id' => $lab_tests,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('sub-packages.index');
    }


    public function edit(SubPackage $sub_package)
    {
        $sub_package->load('packageTests.labTest');
            if (isset($sub_package->packageTests)) {
            $sub_package->lab_tests = $sub_package->packageTests->pluck('lab_test_id');
        }

        $selectedLabTests = null;
        if (isset($sub_package->lab_tests)) {
            $selectedLabTests = LabTest::whereIn('id', $sub_package->lab_tests)->select('id', 'name')->get();
        }
        $lab_tests = LabTest::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Lab/SubPackages/Create', [
            'package' => $sub_package,
            'selectedLabTests' => $selectedLabTests,
            'lab_tests' => $lab_tests,
        ]);
    }
    public function update(SubPackageRequest $request, SubPackage $sub_package)
    {
        DB::beginTransaction();

        $formData = $request->only($sub_package->getFillable());

        if (empty($request->lab_tests) || !is_array($request->lab_tests) || count($request->lab_tests) === 0) {
            SubPackageTest::where('sub_package_id', $sub_package->id)->delete();
            $sub_package->delete();

            SubPackage::create($formData);

            DB::commit();
            return redirect()->route('sub-packages.index')->with('message', 'No lab tests found. SubPackage recreated.');
        }

        $sub_package->update($formData);

        SubPackageTest::where('sub_package_id', $sub_package->id)->delete();
        foreach ($request->lab_tests as $lab_test_id) {
            if (!empty($lab_test_id)) {
                SubPackageTest::create([
                    'sub_package_id' => $sub_package->id,
                    'lab_test_id' => $lab_test_id,
                ]);
            }
        }

        DB::commit();
        return redirect()->route('sub-packages.index')->with('message', 'SubPackage updated successfully.');
    }


    // public function update(SubPackageRequest $request, SubPackage $sub_package)
    // {
    //     // dd($request);
    //     DB::beginTransaction();

    //     $formData = $request->only($sub_package->getFillable());
    //     $sub_package->update($formData);
    //     if (!empty($request->lab_tests) && is_array($request->lab_tests)) {
    //         SubPackageTest::where('sub_package_id', $sub_package->id)->delete();
    //         foreach ($request->lab_tests as $key => $lab_tests) {
    //             if (!empty($lab_tests)) {
    //                 SubPackageTest::create([
    //                     'sub_package_id' => $sub_package->id,
    //                     'lab_test_id' => $lab_tests,
    //                 ]);
    //             }
    //         }
    //     }

    //     DB::commit();

    //     return redirect()->route('sub-packages.index')->with('message', 'SubPackage updated successfully.');
    // }


    public function destroy($id)
    {
        $package = SubPackage::findOrFail($id);
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'SubPackage deleted successfully.');
    }
}
