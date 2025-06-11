<?php

namespace App\Http\Controllers\Lab;

use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\Package;
use App\Models\OPD\Patient;
use XelentAbrar\HospitalDonation\Services\ZfService;
use App\Models\HRMS\Employee;
use XelentAbrar\HospitalLab\Models\Lab\WidalTest;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use Illuminate\Support\Facades\DB;
use XelentAbrar\HospitalLab\Models\Lab\TestWidalDetail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Models\Lab\PatientTestDetail;
use XelentAbrar\HospitalLab\Models\Lab\TestGeneralDetail;
use XelentAbrar\HospitalLab\Services\VoucherAuditService;
use XelentAbrar\HospitalLab\Models\Lab\TestCrossMatchDetail;
use XelentAbrar\HospitalLab\Http\Requests\Lab\PatientTestRequest;

class PatientTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:patient-tests.index')->only('index');
        $this->middleware('checkPermission:patient-tests.create')->only('create', 'store');
        $this->middleware('checkPermission:patient-tests.show')->only('show');
        $this->middleware('checkPermission:patient-tests.edit')->only('edit', 'update');
        $this->middleware('checkPermission:patient-tests.destroy')->only('destroy');
    }
    // public function index()
    // {
    //     $patient_tests = PatientTest::orderBy('created_at', 'desc')->paginate(100);
    //     $user = Auth::user();
    //     $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
    //     return Inertia::render('Lab/PatientTests/Index', [
    //         'patient_tests' => $patient_tests,
    //         'role' => $role,
    //     ]);
    // }
    public function index()
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
            ])
            ->where('test_nature', '!=', 'Other_Receipt')
            ->orderBy('created_at', 'desc');

        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($fromDate && $toDate) {
            $query->whereBetween('test_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('referred_by', 'LIKE', '%' . $search . '%')->orWhere('patient_phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('lab_no', '=', $search)->orWhere('id', '=', $search)
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

        $patient_tests = $query->paginate(perPage: 250);

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        return Inertia::render('Lab/PatientTests/Index', [
            'patient_tests' => $patient_tests,
            'role' => $role,
            'filters' => [
                'search' => $search,
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
        ]);
    }



    public function other_receipt()
    {
        // $query = PatientTest::query()->orderBy('created_at', 'desc');
        $query = PatientTest::query()
            ->with([
                'patientTestDetails.package',
                'patientTestDetails.testGeneralDetails.subPackage',
                'patientTestDetails.testGeneralDetails.labTest',
                'patientTestDetails.testGeneralDetails.testCategory',
                'testWidalDetails.widalTest',
                'testCrossMatchDetails','creator:id,name',
            ])
            ->where('test_nature', '=', 'Other_Receipt')
            ->orderBy('created_at', 'desc');

        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($fromDate && $toDate) {
            $query->whereBetween('test_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('patient_name', 'LIKE', '%' . $search . '%')->orWhere('id', '=', $search)
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

        $patient_tests = $query->paginate(100);

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        return Inertia::render('Lab/PatientTests/OtherReceipt', [
            'patient_tests' => $patient_tests,
            'role' => $role,
            'filters' => [
                'search' => $search,
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
        ]);
    }

    public function create()
    {
        $doctors = Employee::with('doctorCharges')->select('id', 'name', 'doctor_type')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();
        $packages = Package::with(['subPackages.labTests.testCategory'])->whereStatus('active')->orderBy('name')->get();
        // dd($packages);
        $widal_tests = WidalTest::select('id', 'name', 'charges')->orderBy('name')->get();
        $lab_tests = LabTest::select('id', 'name', 'price', 'test_category_id')->where('lab_type', 'lab_test')->where('status', 'active')->orderBy('name')->get();
        $other_tests = LabTest::select('id', 'name', 'price', 'test_category_id')->where('lab_type', 'other_receipts')->where('status', 'active')->orderBy('name')->get();
        $lab_test_details = LabTestDetail::select('id', 'test_report', 'lab_test_id')->orderBy('test_report')->get();
        $patients = Patient::with(['appointments', 'admissions'])->select('id', 'name')->orderBy('name')->get();
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $test_categories = TestCategory::whereStatus('active')->get();
        $sub_packages = SubPackage::with(['labTests.testCategory'])->whereStatus('active')->orderBy('name')->get();
        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
        }

        if(file_exists(base_path('config/donation.php'))) {
            $zf_fees = new ZfService();
            $zf_fees = $zf_fees->getZfFees();
        }
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        // return $role;
        $last_mr_number = Patient::max('mr_number');
        $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
        $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);
        $lastLabNo = PatientTest::max('lab_no');
        return Inertia::render('Lab/PatientTests/Create', [
            'lab_tests' => $lab_tests,
            'other_tests' => $other_tests,
            'lab_test_details' => $lab_test_details,
            'widal_tests' => $widal_tests,
            'patients' => $patients,
            'countries' => $countries,
            'test_categories' => $test_categories,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees ?? null,
            'doctors' => $doctors,
            'packages' => $packages,
            'sub_packages' => $sub_packages,
            'roles' => $role,
            'mr_number' => $new_mr_number,
        ]);
    }


    public function store(PatientTestRequest $request)
    {
        $patient = new PatientTest();
        $patientData = $request->only($patient->getFillable());

        $check_patient = PatientTest::query();

        // Get the fillable fields from the model
        $fillableFields = (new PatientTest())->getFillable();

        $filteredData = array_filter(
            $patientData,
            function ($key) use ($fillableFields) {
                return in_array($key, $fillableFields) && $key !== 'lab_no' && $key !== 'status' && $key !== 'date_sample' && $key !== 'date_report_ready' && $key !== 'date_report_verification' && $key !== 'date_report_collection' && $key !== 'lab_incharge' && $key !== 'doctor_id' && $key !== 'patient_id' && $key !== 'is_printed' && $key !== 'specimen' && $key !== 'free_test' && $key !== 'discount_type' && $key !== 'discount_value' && $key !== 'test_category_id' && $key !== 'file_no' && $key !== 'package_id' && $key !== 'chart_no' && $key !== 'cancel' && $key !== 'lab_serial' && $key !== 'test_time';
            },
            ARRAY_FILTER_USE_KEY
        );
        foreach ($filteredData as $key => $value) {
            $check_patient->where($key, $value);
        }

        $last_patient_id = $check_patient->value('id');

        if ($last_patient_id) {
            if (isset($request->source) && $request->source == 'dashboard') {
                return redirect()->route('dashboard', ['patient_test_id' => $last_patient_id]);
            }
            return redirect()->route('patient-tests.index');
        }

        DB::beginTransaction();
        try {
            $currentTime = now()->format('H:i:s');
            $currentDateTimeInPakistan = now()->setTimezone('Asia/Karachi');
            // $lastLabNo = PatientTest::max('lab_no');
            // $nextLabNo = $lastLabNo + 1;
                // $prefix = $request->test_nature === 'General' ? 'G' : 'OR';
                // $lastLabNo = PatientTest::where('test_nature', $request->test_nature)
                //                 ->max(DB::raw("SUBSTRING(lab_no, 2)"));
                // $nextLabNo = $prefix . ($lastLabNo ? ((int)substr($lastLabNo, 1) + 1) : 1);

                $lastLabNo = PatientTest::where('test_nature', $request->test_nature)
                ->max('lab_no');
                // dd($lastLabNo);

                $nextLabNo = $lastLabNo ? $lastLabNo + 1 : 1;
                $patient_test = PatientTest::create(array_merge(
                $request->only((new PatientTest())->getFillable()),
                [
                    'created_by' => Auth::id(),
                    'lab_no' => $nextLabNo,
                    'date_report_verification' => $currentDateTimeInPakistan,
                ]
            ));
            $patient_test->update(['test_time' => $currentTime]);
            $highestLabSerial = PatientTest::whereNotNull('lab_serial')
                ->orderByRaw('CAST(lab_serial AS UNSIGNED) DESC')
                ->value(DB::raw('CAST(lab_serial AS UNSIGNED)'));
            $labSerial = ($highestLabSerial ?? 0) + 1;

            $labSerialCount = 0;
            if ($request->test_nature == 'General' && !empty($request->patient_test_details) && is_array($request->patient_test_details)) {
                foreach ($request->patient_test_details as $test_detail) {
                    foreach ($test_detail['test_general_details'] ?? [] as $general_detail) {
                        if (isset($general_detail['lab_test']['id'])) {
                            $lab = LabTest::find($general_detail['lab_test']['id']);
                            if ($lab) {
                                if ($lab->lab_type !== 'other_receipts') {
                                    $labSerialCount++;
                                }
                            }
                        }
                    }
                }
            }
            if ($labSerialCount > 0) {
                $patient_test->update(['lab_serial' => $labSerial]);
            }
            if ($request->test_nature == 'General' && !empty($request->patient_test_details) && is_array($request->patient_test_details)) {
                foreach ($request->patient_test_details as $test_detail) {
                    $test_detail_data = [
                        'patient_test_id' => $patient_test->id,
                        'package_id' => $test_detail['selectedPackage']['id'] ?? null,
                        'remarks' => $test_detail['remarks'] ?? null,
                    ];
                    $test_detail_record = PatientTestDetail::create($test_detail_data);

                    if (!empty($test_detail['test_general_details']) && is_array($test_detail['test_general_details'])) {

                        foreach ($test_detail['test_general_details'] as $general_detail) {
                            $lab_test_id = $general_detail['lab_test']['id'] ?? null;

                            if ($lab_test_id) {

                                $lastLabSerial = TestGeneralDetail::where('lab_test_id', $lab_test_id)
                                    ->max('lab_serial') ?? 0;


                                $labSerial = $lastLabSerial + 1;

                                $lab = LabTest::find($lab_test_id);
                                if ($lab) {
                                    if (is_null($lab->serial_no) || $lab->serial_no == 0) {
                                        $lab->serial_no = $labSerial;
                                    } else {
                                        $lab->serial_no = max($lab->serial_no, $labSerial);
                                    }
                                    $lab->save();
                                }

                                $general_test_detail_data = [
                                    'patient_test_detail_id' => $test_detail_record->id,
                                    'lab_test_id' => $lab_test_id,
                                    'result' => $general_detail['result'] ?? null,
                                    'patient_test_id' => $patient_test->id ?? null,
                                    'result_2' => $general_detail['lab_test_detail']['test_report'] ?? null,
                                    'description' => $general_detail['description'] ?? null,
                                    'test_category_id' => $request->patient_test_details[0]['test_category_data']['id'] ?? null,
                                    'lab_test_detail_id' => $general_detail['lab_test_detail_id'] ?? $general_detail['lab_test_detail']['id'] ?? null,
                                    'sub_package_id' => $general_detail['sub_package_id'] ?? $general_detail['sub_package']['id'] ?? null,
                                    'lab_serial' => $labSerial,
                                ];


                                TestGeneralDetail::create($general_test_detail_data);
                            }
                        }

                    }
                }
            } else if ($request->test_nature == 'Other_Receipt' && !empty($request->patient_test_details) && is_array($request->patient_test_details)) {
                foreach ($request->patient_test_details as $test_detail) {
                    $test_detail_data = [
                        'patient_test_id' => $patient_test->id,
                        'package_id' => $test_detail['selectedPackage']['id'] ?? null,
                        'remarks' => $test_detail['remarks'] ?? null,
                    ];
                    $test_detail_record = PatientTestDetail::create($test_detail_data);

                    if (!empty($test_detail['test_general_details']) && is_array($test_detail['test_general_details'])) {

                        foreach ($test_detail['test_general_details'] as $general_detail) {
                            $lab_test_id = $general_detail['lab_test']['id'] ?? null;

                            if ($lab_test_id) {

                                $lastLabSerial = TestGeneralDetail::where('lab_test_id', $lab_test_id)
                                    ->max('lab_serial') ?? 0;


                                $labSerial = $lastLabSerial + 1;

                                $lab = LabTest::find($lab_test_id);
                                if ($lab) {
                                    if (is_null($lab->serial_no) || $lab->serial_no == 0) {
                                        $lab->serial_no = $labSerial;
                                    } else {
                                        $lab->serial_no = max($lab->serial_no, $labSerial);
                                    }
                                    $lab->save();
                                }

                                // dd($request->test_nature);
                                $general_test_detail_data = [
                                    'patient_test_detail_id' => $test_detail_record->id,
                                    'lab_test_id' => $lab_test_id,
                                    'result' => $general_detail['result'] ?? null,
                                    'patient_test_id' => $patient_test->id ?? null,
                                    'result_2' => $general_detail['lab_test_detail']['test_report'] ?? null,
                                    'description' => $general_detail['description'] ?? null,
                                    'test_category_id' => $request->patient_test_details[0]['test_category_data']['id'] ?? null,
                                    'lab_test_detail_id' => $general_detail['lab_test_detail_id'] ?? $general_detail['lab_test_detail']['id'] ?? null,
                                    'sub_package_id' => $general_detail['sub_package_id'] ?? $general_detail['sub_package']['id'] ?? null,
                                    'lab_serial' => $labSerial,
                                ];


                                TestGeneralDetail::create($general_test_detail_data);
                            }
                        }

                    }
                }
            }
            // Handle Widal Test Details
            else if ($request->test_nature == 'Widal' && !empty($request->test_widal_details) && is_array($request->test_widal_details)) {
                foreach ($request->test_widal_details as $test_widal_detail) {
                    $widal_test_detail_data = [
                        'patient_test_id' => $patient_test->id,
                        'widal_test_id' => $test_widal_detail['widal_test']['id'] ?? null,
                        '1_40' => $test_widal_detail['1_40'] ?? null,
                        '1_80' => $test_widal_detail['1_80'] ?? null,
                        '1_160' => $test_widal_detail['1_160'] ?? null,
                        '1_320' => $test_widal_detail['1_320'] ?? null,
                        '1_640' => $test_widal_detail['1_640'] ?? null,
                    ];
                    TestWidalDetail::create($widal_test_detail_data);
                }
            } else if ($request->test_nature == 'Cross_Match') {
                TestCrossMatchDetail::create([
                    'patient_test_id' => $patient_test->id,
                    'donor_name' => $request->test_cross_match_details['donor_name'] ?? null,
                    'donor_blood_group' => $request->test_cross_match_details['donor_blood_group'] ?? null,
                    'donor_rh_factor' => $request->test_cross_match_details['donor_rh_factor'] ?? null,
                    'donor_age' => $request->test_cross_match_details['donor_age'] ?? null,
                    'donor_blood_pressure' => $request->test_cross_match_details['donor_blood_pressure'] ?? null,
                    'donor_anti_hcv' => $request->test_cross_match_details['donor_anti_hcv'] ?? null,
                    'donor_hbsag' => $request->test_cross_match_details['donor_hbsag'] ?? null,
                    'donor_hiv_antibodies' => $request->test_cross_match_details['donor_hiv_antibodies'] ?? null,
                    'donor_cross_match' => $request->test_cross_match_details['donor_cross_match'] ?? null,
                    'blood_bag_no' => $request->test_cross_match_details['blood_bag_no'] ?? null,
                    'blood_component' => $request->test_cross_match_details['blood_component'] ?? null,
                    'blood_volumn' => $request->test_cross_match_details['blood_volumn'] ?? null,
                    'bleeding_date' => $request->test_cross_match_details['bleeding_date'] ?? null,
                    'bag_expiry_date' => $request->test_cross_match_details['bag_expiry_date'] ?? null,
                ]);
            }

            $voucher_audit = new VoucherAuditService();
            $voucher_audit->storeVoucher('lab', $request, 'store', $patient_test->id);

            $patient = Patient::where('phone', $request->patient_phone)->first();
            $patientData = [
                'name' => $request->patient_name,
                'cnic' => $request->patient_cnic,
                'dob' => $request->patient_dob,
                'gender' => $request->patient_gender,
                'relation_name' => $request->patient_relation_name,
                'address' => $request->patient_address,
                'phone' => $request->patient_phone,
            ];

            if (!$patient) {
                // New patient - get max mr_number and increment
                $latestMrNumber = (int) Patient::max('mr_number');
                $mr_number = str_pad($latestMrNumber + 1, 5, '0', STR_PAD_LEFT);
                $patientData['mr_number'] = $mr_number;
                $patient = Patient::create($patientData);
            } else {
                $mr_number = $patient->mr_number;
            }

            DB::commit();

            if (isset($request->source) && $request->source == 'dashboard') {
                return redirect()->route('dashboard', ['patient_test_id' => $patient_test->id]);
            }
            // return redirect()->route('patient-tests.index');
            if ($request->test_nature === 'Other_Receipt') {
                return redirect()->route('patient-tests.other_receipt');
            } else {
                return redirect()->route('patient-tests.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(PatientTest $patient_test)
    {

        if ($patient_test->test_nature == 'General' || $patient_test->test_nature == 'Other_Receipt') {
            $patient_test = $patient_test->loadMissing(['patientTestDetails', 'patientTestDetails.package.subPackages.labTests.testCategory', 'patientTestDetails.testGeneralDetails', 'patientTestDetails.testGeneralDetails.labTest', 'patientTestDetails.testGeneralDetails.labTestDetail', 'patientTestDetails.testGeneralDetails.testCategory', 'testGeneralDetails.labTest', 'patientTestDetails.testGeneralDetails.subPackage']);
            // return $patient_test;
        } else if ($patient_test->test_nature == 'Widal') {
            $patient_test = $patient_test->loadMissing(['testWidalDetails', 'testWidalDetails.widalTest']);
        } else if ($patient_test->test_nature == 'Cross_Match') {
            $patient_test = $patient_test->loadMissing(['testCrossMatchDetails']);
        }
        $patient_test = $patient_test->loadMissing(['doctor', 'package']);

        $doctors = Employee::with('doctorCharges')->select('id', 'name', 'doctor_type')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();

        $packages = Package::with(['subPackages.labTests.testCategory'])->whereStatus('active')->orderBy('name')->get();
        $widal_tests = WidalTest::select('id', 'name', 'charges')->orderBy('name')->get();
        $lab_tests = LabTest::select(['id', 'name', 'price', 'test_category_id'])
            ->where('lab_type', 'lab_test')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $other_tests = LabTest::select(['id', 'name', 'price', 'test_category_id'])
            ->where('lab_type', 'other_receipts')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        // return $other_tests;
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $patient = null;
        if ($patient_test->patient_cnic) {
            $patient = Patient::where('cnic', $patient_test->patient_cnic)->first();
        }
        if (!$patient && $patient_test->patient_phone) {
            $patient = Patient::where('phone', $patient_test->patient_phone)->first();
        }

        $mr_number = $patient ? $patient->mr_number : null;
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $lab_test_details = LabTestDetail::select('id', 'test_report', 'lab_test_id')->get();
        $states = [];
        if ($patient_test->patient_state_id) {
            $states = State::where('country_id', $patient_test->patient_country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        $cities = [];
        if ($patient_test->patient_city_id) {
            $cities = City::where('state_id', $patient_test->patient_state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }

        $test_categories = TestCategory::whereStatus('active')->get();
        $sub_packages = SubPackage::with(['labTests.testCategory'])->whereStatus('active')->get();

        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
            $zf_fees = new ZfService();
            $zf_fees = $zf_fees->getZfFees();
        }

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        return Inertia::render('Lab/PatientTests/Create', [
            'patient_test' => $patient_test,
            'lab_test_details' => $lab_test_details,
            'lab_tests' => $lab_tests,
            'other_tests' => $other_tests,
            'widal_tests' => $widal_tests,
            'patients' => $patients,
            'mr_number' => $mr_number,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'test_categories' => $test_categories,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees ?? null,
            'doctors' => $doctors,
            'packages' => $packages,
            'sub_packages' => $sub_packages,
            'roles' => $role,
        ]);
    }






    public function update(PatientTestRequest $request, PatientTest $patient_test)
    {
        DB::beginTransaction();
            // dd($request);
        try {
            $currentDateTimeInPakistan = now()->setTimezone('Asia/Karachi');

            $formData = $request->only($patient_test->getFillable());
            $formData['updated_by'] = Auth::id();
            $formData['date_report_verification'] = $currentDateTimeInPakistan;
            $voucher_audit = new VoucherAuditService();
            $voucher_audit = $voucher_audit->storeVoucher('lab', $request, 'update', $patient_test->id);

            if ($patient_test->patient_id !== null && $request->patient_id === null) {
                $formData['patient_id'] = $patient_test->patient_id;
            }

            Log::info('Updating patient test:', $formData);
            $patient_test->update($formData);

            if ($request->test_nature && $request->patient_test_details) {
                foreach ($request->patient_test_details as $test_detail) {
                    if (isset($test_detail['test_general_details'])) {
                        TestGeneralDetail::where('patient_test_detail_id', $test_detail['id'])->delete();
                    }
                    PatientTestDetail::where('id', $test_detail['id'])->delete();
                }
                Log::info('Deleted existing patient test details.');

                if ($patient_test->testWidalDetails) {
                    $patient_test->testWidalDetails()->delete();
                }
            }

            if (!empty($request->patient_test_details) && is_array($request->patient_test_details)) {
                foreach ($request->patient_test_details as $test_detail) {
                    $patient_test_detail = PatientTestDetail::updateOrCreate(
                        ['id' => $test_detail['id'] ?? null],
                        [
                            'patient_test_id' => $patient_test->id,
                            'package_id' => $test_detail['package_id'] ?? null,
                            'remarks' => $test_detail['remarks'] ?? null,
                        ]
                    );

                    Log::info('Patient test detail updated or created:', $test_detail);

                    if ($request->test_nature == 'General' && !empty($test_detail['test_general_details']) && is_array($test_detail['test_general_details'])) {
                        foreach ($test_detail['test_general_details'] as $test_general_detail) {
                            $test_category_data = $request->patient_test_details[0]['test_category_data'] ?? [];
                            $test_general_detail_data = [
                                'patient_test_id' => $patient_test->id,
                                'lab_test_id' => $test_general_detail['lab_test']['id'] ?? null,
                                'result' => $test_general_detail['result'] ?? null,
                                'result_2' => $test_general_detail['lab_test_detail']['test_report'] ?? $test_general_detail['result_2'] ?? null,
                                'description' => $test_general_detail['description'] ?? null,
                                'test_category_id' => $test_category_data['id'] ?? null,
                                'lab_test_detail_id' => $test_general_detail['lab_test_detail']['id'] ?? null,
                                'patient_test_detail_id' => $patient_test_detail->id,
                                'sub_package_id' => $test_general_detail['sub_package_id'] ?? null,
                            ];

                            TestGeneralDetail::updateOrCreate(
                                ['id' => $test_general_detail['id'] ?? null],
                                $test_general_detail_data
                            );
                             Log::info('Test general detail updated or created:', $test_general_detail_data);
                        }
                    } else if ($request->test_nature == 'Other_Receipt' && !empty($test_detail['test_general_details']) && is_array($test_detail['test_general_details'])) {
                        foreach ($test_detail['test_general_details'] as $test_general_detail) {
                            $test_category_data = $request->patient_test_details[0]['test_category_data'] ?? [];
                            $test_general_detail_data = [
                                'patient_test_id' => $patient_test->id,
                                'lab_test_id' => $test_general_detail['lab_test']['id'] ?? null,
                                'result' => $test_general_detail['result'] ?? null,
                                'result_2' => $test_general_detail['lab_test_detail']['test_report'] ?? $test_general_detail['result_2'] ?? null,
                                'description' => $test_general_detail['description'] ?? null,
                                'test_category_id' => $test_category_data['id'] ?? null,
                                'lab_test_detail_id' => $test_general_detail['lab_test_detail']['id'] ?? null,
                                'patient_test_detail_id' => $patient_test_detail->id,
                                'sub_package_id' => $test_general_detail['sub_package_id'] ?? null,
                            ];

                            TestGeneralDetail::updateOrCreate(
                                ['id' => $test_general_detail['id'] ?? null],
                                $test_general_detail_data
                            );
                             Log::info('Test general detail updated or created:', $test_general_detail_data);
                        }
                    }
                }
            }


            if ($request->test_nature == 'Widal' && !empty($request->test_widal_details) && is_array($request->test_widal_details)) {
                foreach ($request->test_widal_details as $test_widal_detail) {
                    $test_widal_detail_data = [
                        'patient_test_id' => $patient_test->id,
                        'widal_test_id' => $test_widal_detail['widal_test']['id'] ?? null,
                        '1_40' => $test_widal_detail['1_40'] ?? null,
                        '1_80' => $test_widal_detail['1_80'] ?? null,
                        '1_160' => $test_widal_detail['1_160'] ?? null,
                        '1_320' => $test_widal_detail['1_320'] ?? null,
                        '1_640' => $test_widal_detail['1_640'] ?? null,
                    ];

                    TestWidalDetail::create($test_widal_detail_data);
                     Log::info('Test Widal detail created:', $test_widal_detail_data);
                }
            }

            // Update or create Cross Match details
            if ($request->test_nature == 'Cross_Match' && !empty($request->test_cross_match_details)) {
                $cross_match_data = [
                    'donor_name' => $request->test_cross_match_details['donor_name'] ?? null,
                    'donor_blood_group' => $request->test_cross_match_details['donor_blood_group'] ?? null,
                    'donor_rh_factor' => $request->test_cross_match_details['donor_rh_factor'] ?? null,
                    'donor_age' => $request->test_cross_match_details['donor_age'] ?? null,
                    'donor_blood_pressure' => $request->test_cross_match_details['donor_blood_pressure'] ?? null,
                    'donor_anti_hcv' => $request->test_cross_match_details['donor_anti_hcv'] ?? null,
                    'donor_hbsag' => $request->test_cross_match_details['donor_hbsag'] ?? null,
                    'donor_hiv_antibodies' => $request->test_cross_match_details['donor_hiv_antibodies'] ?? null,
                    'donor_cross_match' => $request->test_cross_match_details['donor_cross_match'] ?? null,
                    'blood_bag_no' => $request->test_cross_match_details['blood_bag_no'] ?? null,
                    'blood_component' => $request->test_cross_match_details['blood_component'] ?? null,
                    'blood_volumn' => $request->test_cross_match_details['blood_volumn'] ?? null,
                    'bleeding_date' => $request->test_cross_match_details['bleeding_date'] ?? null,
                    'bag_expiry_date' => $request->test_cross_match_details['bag_expiry_date'] ?? null,
                ];

                TestCrossMatchDetail::updateOrCreate(
                    ['patient_test_id' => $patient_test->id],
                    $cross_match_data
                );
                // Log::info('Test Cross Match detail updated or created:', $cross_match_data);
            }
            // dd($request->all());
            $mr_number = $request->input('mr_number');
            $patient = Patient::where('phone', $request->patient_phone)->first();
            $patientDatas = [
                'name' => $request->patient_name,
                'cnic' => $request->patient_cnic,
                'dob' => $request->patient_dob,
                'gender' => $request->patient_gender,
                'relation_name' => $request->patient_relation_name,
                'address' => $request->patient_address,
                'phone' => $request->patient_phone,
                'mr_number' => $mr_number,
            ];
            if (!$patient) {
                $patientDatas['mr_number'] = $mr_number;
                $patient = Patient::create($patientDatas);
            } else {
                unset($patientDatas['mr_number']);
                $patient->update($patientDatas);
            }

            DB::commit();
            // Log::info('Transaction committed successfully.');
            if ($request->test_nature === 'Other_Receipt') {
                // return redirect()->route('patient-tests.other_receipt');
                return redirect()->route('patient-tests.other_receipt')->with('message', 'Other_Receipt updated successfully.');

            } else {
                return redirect()->route('patient-tests.index')->with('message', 'Patient test updated successfully.');
            }
            // return redirect()->route('patient-tests.index')->with('message', 'Patient test updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction rolled back due to error:', ['exception' => $e->getMessage()]);
            return redirect()->route('patient-tests.index')->with('error', 'An error occurred while updating the patient test: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $patient_test = PatientTest::findOrFail($id);
        // if ($patient_test->testGeneralDetails()) {
        //     $patient_test->testGeneralDetails()->delete();
        // }
        if ($patient_test->patientTestDetails()->exists()) {
            $patient_test->patientTestDetails->each(function ($detail) {
                if ($detail->testGeneralDetails()->exists()) {
                    $detail->testGeneralDetails()->delete();
                }
                $detail->delete();
            });
        }
        if ($patient_test->testWidalDetails()) {
            $patient_test->testWidalDetails()->delete();
        }
        if ($patient_test->testCrossMatchDetails()) {
            $patient_test->testCrossMatchDetails()->delete();
        }
        $patient_test->delete();

        DB::commit();

        return redirect()->route('patient-tests.index')->with('success', 'Patient test deleted successfully.');
    }

    public function print(PatientTest $patient_test)
    {
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        if (!$role && $patient_test->is_printed == 1 && $patient_test->is_printed == 2) {
            return redirect()->route('patient-tests.index')->with('error', 'This print has already been printed.');
        }
        $newPrintedStatus = $patient_test->is_printed == 0 ? 1 : 2;
        $patient_test->update(['is_printed' => $newPrintedStatus]);
        // $patient_test->update(['is_printed' => 1]);
        if(file_exists(base_path('config/donation.php'))) {
            $patient_test->load(['careoff']);
        }
        $patient_test->load(['patientTestDetails.package','creator:id,name', 'patientTestDetails.testGeneralDetails.subPackage', 'patientTestDetails.testGeneralDetails', 'patientTestDetails.testGeneralDetails.labTest', 'patientTestDetails.testGeneralDetails.testCategory', 'testWidalDetails', 'testWidalDetails.widalTest', 'testCrossMatchDetails']);
        $mr_number = null;
        if ($patient_test->patient_phone) {
            $patient = Patient::where('phone', $patient_test->patient_phone)->first();
            $mr_number = $patient?->mr_number;
        }
        $user = Auth::user();
        return Inertia::render('Lab/PatientTests/Print', [
            'patient_test' => $patient_test,
            'user' => $user,
            'mr_number' => $mr_number,
        ]);
    }
    public function detailPrint(PatientTest $patient_test)
    {
        $patient_test->load([
            'patientTestDetails.testGeneralDetails',
            'patientTestDetails.testGeneralDetails.testCategory',
            'patientTestDetails.testGeneralDetails.subPackage',
            'patientTestDetails.testGeneralDetails.labTest.testCategory',
            'patientTestDetails.testGeneralDetails.labTest.unit',
            'testWidalDetails',
            'testWidalDetails.widalTest',
            'testCrossMatchDetails',
            'doctor.education',
            'package.testCategory',
            'patientTestDetails.package.testCategory',
            'creator:id,name','updator:id,name',
        ]);

        $lab_results = [];
        $package_results = [];

        if ($patient_test->patientTestDetails->isNotEmpty()) {
            $patientTestDetails = $patient_test->patientTestDetails;

            foreach ($patientTestDetails as $detail) {
                if ($detail->package) {
                    $package_id = $detail->package->id;
                    $package_name = $detail->package->name;
                    $package_category = $detail->package->testCategory->name ?? '';
                    $remarks = $detail->remarks;

                    if (!isset($package_results[$package_id])) {
                        $package_results[$package_id] = [
                            'package_name' => $package_name,
                            'packageId' => $package_id,
                            'package_category' => $package_category,
                            'remarks' => $remarks,
                            'tests' => []
                        ];
                    }

                    if ($patient_test->test_nature == 'General' || $patient_test->test_nature == 'Other_Receipt') {
                        foreach ($detail->testGeneralDetails as $test_general_details) {
                            if ($test_general_details?->subPackage?->name) {
                                $parms = [
                                    'min_value' => $test_general_details?->labTest?->min_value,
                                    'max_value' => $test_general_details?->labTest?->max_value,
                                    'test_name' => $test_general_details?->labTest?->name,
                                    'category' => $test_general_details?->labTest?->testCategory?->name,
                                    'result' => $test_general_details?->result,
                                    'result_2' => $test_general_details?->result_2,
                                    'description' => $test_general_details?->description,
                                    'value_range' => $test_general_details?->labTest?->value_range,
                                    'unit' => $test_general_details?->labTest?->unit?->name,
                                    'subPackage' => $test_general_details?->subPackage?->name
                                ];
                                $package_results[$package_id]['tests'][] = $parms;
                            }
                        }
                    }
                } else {
                    if ($patient_test->test_nature == 'General' || $patient_test->test_nature == 'Other_Receipt') {
                        foreach ($detail->testGeneralDetails as $test_general_details) {
                            $parms = [
                                'min_value' => $test_general_details?->labTest?->min_value,
                                'max_value' => $test_general_details?->labTest?->max_value,
                                'test_name' => $test_general_details?->labTest?->name,
                                'category' => $test_general_details?->labTest?->testCategory?->name,
                                'result' => $test_general_details?->result,
                                'description' => $test_general_details?->description,
                                'result_2' => $test_general_details?->result_2,
                                'value_range' => $test_general_details?->labTest?->value_range,
                                'unit' => $test_general_details?->labTest?->unit?->name,
                                'subPackage' => $test_general_details?->subPackage?->name
                            ];
                            $lab_results[] = $parms;
                        }
                    }
                }
            }
        }

        $package_results = collect($package_results)->map(function ($package) {
            $package['tests'] = collect($package['tests'])->sortBy('category')->values()->all();
            return $package;
        })->sortBy('package_category')->values()->all();

        $lab_results = collect($lab_results)->sortBy('category')->values()->all();

        $bloodTestConsent = request()->query('bloodTestConsent', 0);
        return Inertia::render('Lab/PatientTests/DetailPrint', [
            'patient_test' => $patient_test,
            'package_results' => $package_results,
            'lab_results' => $lab_results,
            'bloodTestConsent' => $bloodTestConsent
        ]);
    }


    // public function detailPrint(PatientTest $patient_test)
    // {
    //     $patient_test->load([
    //         'patientTestDetails.testGeneralDetails',
    //         'patientTestDetails.testGeneralDetails.testCategory',
    //         'patientTestDetails.testGeneralDetails.subPackage',
    //         'patientTestDetails.testGeneralDetails.labTest.testCategory',
    //         'patientTestDetails.testGeneralDetails.labTest.unit',
    //         'testWidalDetails',
    //         'testWidalDetails.widalTest',
    //         'testCrossMatchDetails',
    //         'doctor.education','package.testCategory',
    //         'patientTestDetails.package.testCategory','creator:id,name'
    //     ]);

    //     $lab_results = [];
    //     $package_results = [];

    //     if ($patient_test->patientTestDetails->isNotEmpty()) {
    //         $patientTestDetails = $patient_test->patientTestDetails;

    //         foreach ($patientTestDetails as $detail) {
    //             if ($detail->package) {
    //                 $package_id = $detail->package->id;
    //                 $package_name = $detail->package->name;
    //                 $package_category = $detail->package->testCategory->name ?? '';
    //                 $remarks = $detail->remarks;

    //                 if (!isset($package_results[$package_id])) {
    //                     $package_results[$package_id] = [
    //                         'package_name' => $package_name,
    //                         'packageId' => $package_id,
    //                         'package_category' => $package_category,
    //                         'remarks' => $remarks,
    //                         'tests' => []
    //                     ];
    //                 }

    //                 if ($patient_test->test_nature == 'General' || $patient_test->test_nature == 'Other_Receipt') {
    //                     foreach ($detail->testGeneralDetails as $test_general_details) {
    //                         if ($test_general_details?->subPackage?->name) {
    //                             $parms = [
    //                                 'min_value' => $test_general_details?->labTest?->min_value,
    //                                 'max_value' => $test_general_details?->labTest?->max_value,
    //                                 'test_name' => $test_general_details?->labTest?->name,
    //                                 'category' => $test_general_details?->labTest?->testCategory?->name,
    //                                 'result' => $test_general_details?->result,
    //                                 'result_2' => $test_general_details?->result_2,
    //                                 'description' => $test_general_details?->description,
    //                                 'value_range' => $test_general_details?->labTest?->value_range,
    //                                 'unit' => $test_general_details?->labTest?->unit?->name,
    //                                 'subPackage' => $test_general_details?->subPackage?->name
    //                             ];
    //                             $package_results[$package_id]['tests'][] = $parms;
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 if ($patient_test->test_nature == 'General' || $patient_test->test_nature == 'Other_Receipt') {
    //                     foreach ($detail->testGeneralDetails as $test_general_details) {
    //                         // if ($test_general_details?->subPackage?->name) {
    //                         $parms = [
    //                             'min_value' => $test_general_details?->labTest?->min_value,
    //                             'max_value' => $test_general_details?->labTest?->max_value,
    //                             'test_name' => $test_general_details?->labTest?->name,
    //                             'category' => $test_general_details?->labTest?->testCategory?->name,
    //                             'result' => $test_general_details?->result,
    //                             'description' => $test_general_details?->description,
    //                             'result_2' => $test_general_details?->result_2,
    //                             'value_range' => $test_general_details?->labTest?->value_range,
    //                             'unit' => $test_general_details?->labTest?->unit?->name,
    //                             'subPackage' => $test_general_details?->subPackage?->name
    //                         ];
    //                         $lab_results[] = $parms;
    //                         // }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     foreach ($package_results as &$package) {
    //         // $package['tests'] = collect($package['tests'])->sortBy('category')->values()->all();
    //         $package['tests'] = collect($package['tests'])->values()->all();
    //     }
    //     $bloodTestConsent = request()->query('bloodTestConsent', 0);
    //     $package_results = array_values($package_results);
    //     $lab_results = array_values($lab_results);
    //     return Inertia::render('Lab/PatientTests/DetailPrint', [
    //         'patient_test' => $patient_test,
    //         'package_results' => $package_results,
    //         'lab_results' => $lab_results,
    //         'bloodTestConsent' => $bloodTestConsent
    //     ]);
    // }

    public function cancelReceipt(PatientTest $patient_test)
    {
        $patient_test = $patient_test->loadMissing(['patientTestDetails.package.subPackages.labTests.testCategory', 'patientTestDetails.testGeneralDetails', 'patientTestDetails.testGeneralDetails.labTest', 'patientTestDetails.testGeneralDetails.labTestDetail', 'patientTestDetails.testGeneralDetails.testCategory', 'testGeneralDetails.labTest', 'patientTestDetails.testGeneralDetails.subPackage'])->toArray();
        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('lab', $patient_test, 'store', $patient_test['id'], 'reverse');
        PatientTest::whereId($patient_test['id'])->update([
            'cancel' => '1'
        ]);

        return redirect()->route('patient-tests.index')->with('success', 'Patient test deleted successfully.');
    }



}
