<?php

namespace App\Http\Controllers\Lab;

use App\Models\Role;
use Inertia\Inertia;
use XelentAbrar\HospitalLab\Models\Lab\Unit;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\LabTestRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;

class LabTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:lab-tests.index')->only('index');
        $this->middleware('checkPermission:lab-tests.create')->only('create', 'store');
        $this->middleware('checkPermission:lab-tests.show')->only('show');
        $this->middleware('checkPermission:lab-tests.edit')->only('edit', 'update');
        $this->middleware('checkPermission:lab-tests.destroy')->only('destroy');
    }
    // public function index()
    // {
    //     $lab_tests = LabTest::with(['testCategory:id,name', 'unit:id,name'])->orderBy('created_at', 'desc')->paginate(100);

    //     return Inertia::render('Lab/LabTests/Index', [
    //         'lab_tests' => $lab_tests,
    //     ]);
    // }
    public function index()
    {
        $query = LabTest::with(['testCategory:id,name', 'unit:id,name','creator:id,name'])->where('lab_type','lab_test');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];

            $query = $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('testCategory', function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        $lab_tests = $query->orderByRaw("status = 'inactive', name")
        ->paginate(100);
                    $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();

        return Inertia::render('Lab/LabTests/Index', [
            'lab_tests' => $lab_tests,
            'role' => $role,
            'filters' => request()->only(['search']),

        ]);
    }
    public function OutDoor()
    {
        $query = LabTest::with(['testCategory:id,name', 'unit:id,name','creator:id,name'])->where('lab_type','lab_test')->where('lab_test_type','outdoor_test');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];

            $query = $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('testCategory', function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        $lab_tests = $query->orderByRaw("status = 'inactive', name")
        ->paginate(100);
                    $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();

        return Inertia::render('Lab/LabTests/OutDoor', [
            'lab_tests' => $lab_tests,
            'role' => $role,
            'filters' => request()->only(['search']),

        ]);
    }
    public function OtherReceipt()
    {
        $query = LabTest::with(['testCategory:id,name', 'unit:id,name','creator:id,name'])->where('lab_type','other_receipts');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];

            $query = $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('testCategory', function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        $lab_tests = $query->orderByRaw("status = 'inactive', name")
        ->paginate(100);
                    $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();

        return Inertia::render('Lab/LabTests/OtherReceipt', [
            'lab_tests' => $lab_tests,
            'role' => $role,
            'filters' => request()->only(['search']),

        ]);
    }

    public function create()
    {
        $units = Unit::select('id', 'name')->orderBy('name')->get();
        $test_categories = TestCategory::select('id', 'name')->orderBy(column: 'name')->get();
        $test_groups = LabTest::select('id', 'name')->orderBy('name')->get();
        if(file_exists(base_path('config/accounts.php'))) {
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('Lab/LabTests/Create', [
            'test_groups' => $test_groups,
            'test_categories' => $test_categories,
            'units' => $units,
            'coa' => $coa ?? null,
        ]);
    }

    // public function store(LabTestRequest $request)
    // {
    //     DB::beginTransaction();

    //     $lab_test = new LabTest();
    //     $formData = $request->only($lab_test->getFillable());

    //     LabTest::create($formData);

    //     DB::commit();

    //     return redirect()->route('lab-tests.index');
    // }

    // public function store(LabTestRequest $request)
    // {
    //     DB::beginTransaction();

    //     try {

    //         $labTest = LabTest::create($request->only((new LabTest())->getFillable()));
    //           if ($request->has('details')) {
    //             foreach ($request->details as $detail) {
    //                 $detail['lab_test_id'] = $labTest->id;

    //                 LabTestDetail::create($detail);
    //             }
    //         }
    //         DB::commit();
    //         return redirect()->route('lab-tests.index')->with('success', 'Lab Test created successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
    //     }
    // }
    public function store(LabTestRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->request->add(['created_by' => auth()->id()]);
            $data = $request->only((new LabTest())->getFillable());
            $data['created_by'] = auth()->id();
            $labTest = LabTest::create($data);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $detail['lab_test_id'] = $labTest->id;

                    LabTestDetail::create($detail);
                }
            }

            DB::commit();

            if ($labTest->lab_type === 'other_receipts') {
                return redirect()->route('lab-tests.other-receipt')->with('success', 'Lab Test created successfully.');
            }
            return redirect()->route('lab-tests.index')->with('success', 'Lab Test created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function edit(LabTest $lab_test)
    {
        $lab_test->loadMissing(['details']);
        $units = Unit::select('id', 'name')->orderBy('name')->get();
        $test_categories = TestCategory::select('id', 'name')->orderBy('name')->get();
        if(file_exists(base_path('config/accounts.php'))) {
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('Lab/LabTests/Create', [
            'lab_test' => $lab_test,
            'units' => $units,
            'test_categories' => $test_categories,
            'coa' => $coa ?? null,
        ]);
    }


    // public function update(LabTestRequest $request, LabTest $lab_test)
    // {
    //     DB::beginTransaction();

    //     $formData = $request->only($lab_test->getFillable());
    //     $lab_test->update($formData);

    //     DB::commit();

    //     return redirect()->route('lab-tests.index')->with('message', 'Lab test updated successfully.');
    // }
    public function update(LabTestRequest $request, LabTest $lab_test)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $request->request->add(['updated_by' => auth()->id()]);

            $lab_test->update($request->only($lab_test->getFillable()));
            if ($request->has('details')) {
                $lab_test->details()->delete();
            foreach ($request->input('details') as $detail) {
                    $lab_test->details()->create([
                        'test_report' => $detail['test_report'],
                        'lab_test_id' => $lab_test->id,
                    ]);
                }
            }
            DB::commit();
            if ($lab_test->lab_type === 'other_receipts') {
                return redirect()->route('lab-tests.other-receipt')->with('success', 'Lab Test created successfully.');
            }
            return redirect()->route('lab-tests.index')->with('message', 'Lab test updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    public function destroy($id)
    {
        $lab_test = LabTest::findOrFail($id);
        $lab_test->delete();

        return redirect()->route('lab-tests.index')->with('success', 'Lab test deleted successfully.');
    }
}
