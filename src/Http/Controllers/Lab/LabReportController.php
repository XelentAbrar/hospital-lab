<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use Illuminate\Http\Request;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Models\Lab\PatientTestDetail;
use XelentAbrar\HospitalLab\Models\Lab\TestGeneralDetail;
use DateTime;

class LabReportController extends Controller
{
    public function searchByTestId($id)
    {
        $query = PatientTest::query()
            ->with([
                'patientTestDetails.package',
                'patientTestDetails.testGeneralDetails.subPackage',
                'patientTestDetails.testGeneralDetails.labTest',
                'patientTestDetails.testGeneralDetails.testCategory',
                'testWidalDetails.widalTest',
                'testCrossMatchDetails',
                'creator:id,name'
            ]);

        $query->where('id', '=', $id);

        $patient_test = $query->first();

        if (!$patient_test) {
            return response()->json(['message' => 'Patient test not found'], 404);
        }

        return response()->json([
            'patient_test' => $patient_test,
        ]);
    }
    public function TestResult(Request $request)
    {
        $query = PatientTest::query()
            ->with([
                'patientTestDetails.package',
                'patientTestDetails.testGeneralDetails.subPackage',
                'patientTestDetails.testGeneralDetails.labTest',
                'patientTestDetails.testGeneralDetails.testCategory',
                'testWidalDetails.widalTest',
                'testCrossMatchDetails',
                'creator:id,name'
            ]);

        $patient_test = $query->paginate(100);

        if (!$patient_test) {
            return response()->json(['message' => 'Patient test not found'], 404);
        }

        return response()->json([
            'patient_test' => $patient_test,
        ]);
    }
    public function TestCountReport(Request $request)
    {
        // $query = TestGeneralDetail::with(['labTest', 'testCategory','labTest.testCategory'])
        //     ->selectRaw('lab_test_id, COUNT(*) as total_tests')
        //     ->groupBy('lab_test_id');
        $query = TestGeneralDetail::with(['labTest', 'testCategory', 'labTest.testCategory'])
        ->selectRaw('lab_test_id, COUNT(*) as total_tests')
        ->whereHas('labTest', function ($query) {
            $query->where('status', 'active')->where('lab_type','other_receipts');
        })
        ->groupBy('lab_test_id');

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }
        $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);

        if ($search) {
            $query->whereHas('labTest', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('labTest.testCategory', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $reports = $query->get()->map(function ($item) {
            $labTest = $item->labTest;
            $testCategory = $labTest?->testCategory;
            return [
                'test_category' => $testCategory?->name ?? 'null',
                'test_name' => $labTest?->name,
                'test_Count' => $item?->total_tests,
                'amount' => $labTest?->price,
                'total_amount' => $labTest?->price * $item?->total_tests,
            ];
        });

        return Inertia::render('Lab/Reports/TotalTest', [
            'reports' => $reports,
            'search' => $request->input('search', ''),
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }
    public function TestCountByPackage(Request $request)
    {
        $query = PatientTestDetail::with(['package.testCategory'])
            ->selectRaw('package_id, COUNT(*) as total_tests')
            ->groupBy('package_id')
            ->whereHas('package', function ($query) {
                $query->where('status', 'active');
            });

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);

        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('package', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $reports = $query->get()->map(function ($item) {
            $package = $item->package;
            $testCategory = $package ? $package->testCategory : null;
            return [
                'package_name' => $package ? $package->name : 'null',
                'category_name' => $testCategory ? $testCategory->name : 'null',
                'package_count' => $item->total_tests,
                'amount' => $package ? $package->price : 0,
                'total_amount' => ($package ? $package->price : 0) * $item->total_tests,
            ];
        });

        return Inertia::render('Lab/Reports/PackageTestCount', [
            'reports' => $reports,
            'search' => $request->input('search', ''),
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function TestReferralReport(Request $request)
    {
        $from_date = $request->input('from_date', date('Y-m-d'));
        $to_date = $request->input('to_date', date('Y-m-d'));
        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        $query = PatientTest::with(['patientTestDetails.package',
            'patientTestDetails.testGeneralDetails.labTest',
            'patientTestDetails.testGeneralDetails.testCategory'
        ])
        ->whereNotNull('referred_by');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('referred_by', 'like', '%' . $search . '%');
        }

        $patientTests = $query->get()->map(function ($patientTest) {
            return [
                'patient_name' => $patientTest->patient_name,
                'referred_by' => $patientTest->referred_by,
                'payment' => $patientTest->total_amount,
                'date' => $patientTest->created_at->format('Y-m-d'),
                'test_details' => $patientTest->patientTestDetails->flatMap(function ($detail) {
                    return $detail->testGeneralDetails->map(function ($generalDetail) use ($detail) {
                        return [
                            'package_name' => optional($detail->package)->name ?? '',
                            'test_category' => optional($generalDetail->testCategory)->name ?? '',
                            'lab_test_name' => optional($generalDetail->labTest)->name ?? '',
                            'test_name' => $generalDetail->name ?? '',
                            'test_result' => $generalDetail->result ?? '',
                        ];
                    });
                }),
            ];
        });

        return Inertia::render('Lab/Reports/ReferralBy', [
            'reports' => $patientTests,
            'search' => $request->input('search', ''),
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function PaymentReport(Request $request)
    {

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        $search = '';
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
        }

        $query = PatientTest::where('status', 'Completed')
                            ->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);

        if ($search) {
            $query->where('patient_name', 'like', '%' . $search . '%');
        }

        $reports = $query->select('patient_name','total_amount')
                         ->get();

        return Inertia::render('Lab/Reports/PaymentReport', [
            'reports' => $reports,
            'search' => $search,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

public function LabCareOFFReport()
{
    $query = PatientTest::query()
        ->with([
            'patientTestDetails.package',
            'patientTestDetails.testGeneralDetails.subPackage',
            'patientTestDetails.testGeneralDetails.labTest',
            'patientTestDetails.testGeneralDetails.testCategory',
            'testWidalDetails.widalTest',
            'testCrossMatchDetails'
        ])
        ->orderBy('created_at', 'desc');

    $search = isset($_GET['search']) ? $_GET['search'] : null;

    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
        if (!$from_date) {
            throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
        }
        $from_date = $from_date->format('Y-m-d H:i');
    } else {
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    }

    if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
        if (!$to_date) {
            throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
        }
        $to_date = $to_date->format('Y-m-d H:i');
    } else {
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i:s', strtotime($from_date)); // Ensure seconds are included
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i:s', strtotime($to_date)); // Ensure seconds are included

    $query->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $search) {
        $query->whereBetween('test_date', [$from_date_only, $to_date_only])
            ->whereBetween('test_time', [$from_time_only, $to_time_only]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('referred_by', 'LIKE', '%' . $search . '%')
                    ->orWhere('lab_no', '=', $search)
                    ->orWhereHas('patientTestDetails.package', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.labTest', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.subPackage', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
            });
        }
    });

    $patient_tests = $query->get();
    $user = Auth::user();
    $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

    return Inertia::render('Lab/Reports/LabCareOff', [
        'patient_tests' => $patient_tests,
        'role' => $role,
        'filters' => [
            'search' => $search,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ],
    ]);
}

public function SummaryByLabTestReport()
{
    $labTests = LabTest::select('id', 'name')->orderBy('name')->get();
    $search = "";
    $lab_test_id = null;
    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

    if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
        if (!$from_date) {
            throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
        }
        $from_date = $from_date->format('Y-m-d H:i');
    } else {
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    }

    if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
        if (!$to_date) {
            throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
        }
        $to_date = $to_date->format('Y-m-d H:i');
    } else {
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    }

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = $_GET['search'];
    }
    if (isset($_GET['lab_test_id']) && $_GET['lab_test_id'] != '') {
        $lab_test_id = $_GET['lab_test_id'];
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i:s', strtotime($from_date));
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i:s', strtotime($to_date));

    $labTestsQuery = PatientTest::with([
        'patientTestDetails.package',
        'patientTestDetails.testGeneralDetails.subPackage',
        'patientTestDetails.testGeneralDetails.labTest',
        'patientTestDetails.testGeneralDetails.testCategory',
        'testWidalDetails.widalTest',
        'testCrossMatchDetails', 'careoff', 'zf', 'testGeneralDetails'
    ])
        ->whereBetween('test_date', [$from_date_only, $to_date_only])
        ->whereBetween('test_time', [$from_time_only, $to_time_only]);

    if ($lab_test_id) {
        $labTestsQuery = $labTestsQuery->whereHas('testGeneralDetails', function ($query) use ($lab_test_id) {
            $query->where('lab_test_id', $lab_test_id);
        });
    }

    if ($search) {
        $labTestsQuery = $labTestsQuery->where(function ($q) use ($search) {
            $q->whereHas('testGeneralDetails.labTest', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('package', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('testGeneralDetails.subPackage', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    $labTestsResults = $labTestsQuery->get();
    $totalLabAmount = 0;
    $totalZFFee = 0;
    $totalDonorFee = 0;
    $totalDiscountValue = 0;

    foreach ($labTestsResults as $labTestResult) {
        $totalAmount = $labTestResult->total_amount ?? 0;
        if(file_exists(base_path('config/donation.php'))) {
            $zfFee = $labTestResult->zf_fee ?? 0;
            $donorFee = $labTestResult->donor_fee ?? 0;
        }
        $discountValue = $labTestResult->discount_value ?? 0;

        $totalLabAmount += (float) $totalAmount;
        if(file_exists(base_path('config/donation.php'))) {
            $totalZFFee += (float) $zfFee;
            $totalDonorFee += (float) $donorFee;
        }
        $totalDiscountValue += (float) $discountValue;
    }

    return Inertia::render('Lab/Reports/SummaryByLabTestReport', [
        'lab_test_id' => $lab_test_id,
        'labTests' => $labTests,
        'reports' => $labTestsResults,
        'from_date' => date('d-m-Y H:i', strtotime($from_date)),
        'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        'search' => $search,
        'totalLabAmount' => $totalLabAmount,
        'totalZFFee' => $totalZFFee,
        'totalDonorFee' => $totalDonorFee,
        'totalDiscountValue' => $totalDiscountValue,
    ]);
}

public function labTestDetailReport()
{
    $labTests = LabTest::select('id', 'name')->orderBy('name')->get();

    $from_date = request('from_date') ? date('Y-m-d H:i', strtotime(request('from_date'))) : date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = request('to_date') ? date('Y-m-d H:i', strtotime(request('to_date'))) : date('Y-m-d H:i', strtotime('today 23:59'));
    $lab_test_id = request('lab_test_id') ?? null;
    $search = request('search') ?? "";

    $labTestsQuery = PatientTest::with([
        'patientTestDetails.package',
        'patientTestDetails.testGeneralDetails.subPackage',
        'patientTestDetails.testGeneralDetails.labTest',
        'patientTestDetails.testGeneralDetails.testCategory',
        'testWidalDetails.widalTest',
        'testCrossMatchDetails',
        ])
        ->whereBetween('test_date', [date('Y-m-d', strtotime($from_date)), date('Y-m-d', strtotime($to_date))]);
        if(file_exists(base_path('config/donation.php'))) {
            $labTestsQuery = $labTestsQuery->with(['careoff','zf']);
        }

    if ($lab_test_id) {
        $labTestsQuery->whereHas('patientTestDetails.testGeneralDetails', function ($query) use ($lab_test_id) {
            $query->where('lab_test_id', $lab_test_id);
        });
    }

    if ($search) {
        $labTestsQuery->where(function ($query) use ($search) {
            $query->whereHas('patientTestDetails.testGeneralDetails.labTest', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('patientTestDetails.package', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('patientTestDetails.testGeneralDetails.subPackage', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    $labTestsResults = $labTestsQuery->get();
    $finalResults = [];
    $totalLabAmount = 0;
    $totalZFFee = 0;
    $totalDonorFee = 0;
    $totalDiscountValue = 0;

    foreach ($labTestsResults as $labTestResult) {
        foreach ($labTestResult->patientTestDetails as $testDetail) {
            foreach ($testDetail->testGeneralDetails as $generalDetail) {
                $labTestName = $generalDetail->labTest->name ?? null;
                $packageName = $testDetail->package->name ?? null;
                $subPackageName = $generalDetail->subPackage->name ?? null;

                // Add a new row for each unique combination
                $finalResults[] = [
                    'lab_test' => $labTestName,
                    'package' => $packageName,
                    'sub_package' => $subPackageName,
                    'total_amount' => $labTestResult->total_amount ?? 0,
                    'patient_name' => $labTestResult->patient_name ?? '',
                    'patient_age' => $labTestResult->patient_age ?? '',
                    'zf_fee' => $labTestResult->zf_fee ?? 0,
                    'donor_fee' => $labTestResult->donor_fee ?? 0,
                    'discount_value' => $labTestResult->discount_value ?? 0,
                ];

                // Accumulate totals
                $totalLabAmount += $labTestResult->total_amount ?? 0;
                if(file_exists(base_path('config/donation.php'))) {
                    $totalZFFee += $labTestResult->zf_fee ?? 0;
                    $totalDonorFee += $labTestResult->donor_fee ?? 0;
                }
                $totalDiscountValue += $labTestResult->discount_value ?? 0;
            }
        }
    }

    return Inertia::render('Lab/Reports/LabTestDetailReport', [
        'lab_test_id' => $lab_test_id,
        'labTests' => $labTests,
        'reports' => $finalResults,
        'from_date' => $to_date ? date('d-m-Y', strtotime($to_date)) : null,
        'to_date' => $to_date ? date('d-m-Y', strtotime($to_date)) : null,
        'search' => $search,
        'totalLabAmount' => $totalLabAmount,
        'totalZFFee' => $totalZFFee,
        'totalDonorFee' => $totalDonorFee,
        'totalDiscountValue' => $totalDiscountValue,
    ]);
}

public function CriticalPatientsReport()
{
    $query = PatientTest::query()
        ->with([
            'patientTestDetails.package',
            'patientTestDetails.testGeneralDetails.subPackage',
            'patientTestDetails.testGeneralDetails.labTest',
            'patientTestDetails.testGeneralDetails.testCategory',
            'testWidalDetails.widalTest',
            'testCrossMatchDetails'
        ])
        ->where('status', 'Critical') // Filter for Critical patients
        ->orderBy('created_at', 'desc');

    $search = isset($_GET['search']) ? $_GET['search'] : null;

    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        $from_date = date('Y-m-d H:i', strtotime($_GET['from_date']));
    }
    if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        $to_date = date('Y-m-d H:i', strtotime($_GET['to_date']));
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i:s', strtotime($from_date)); // Ensure seconds are included
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i:s', strtotime($to_date)); // Ensure seconds are included

    $query->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $search) {
        $query->whereBetween('test_date', [$from_date_only, $to_date_only])
            ->whereBetween('test_time', [$from_time_only, $to_time_only]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('referred_by', 'LIKE', '%' . $search . '%')
                    ->orWhere('lab_no', '=', $search)
                    ->orWhereHas('patientTestDetails.package', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.labTest', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.subPackage', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
            });
        }
    });

    $critical_patients = $query->get();
    $user = Auth::user();
    $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

    return Inertia::render('Lab/Reports/CriticalPatients', [
        'patient_tests' => $critical_patients,
        'role' => $role,
        'filters' => [
            'search' => $search,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ],
    ]);
}


}

