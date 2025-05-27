<?php

namespace XelentAbrar\HospitalDonation\Http\Controllers\Donation;

use Inertia\Inertia;
use App\Models\OPD\Appointment;
use XelentAbrar\HospitalIpd\Models\IPD\Admission;
use XelentAbrar\HospitalLab\Models\LAB\PatientTest;
use XelentAbrar\HospitalDonation\Http\Controllers\Controller;

class ReportController extends Controller
{
    
    public function zfDetailReport()
    {
        if(!file_exists(base_path('config/hrms.php'))) {
            return null;
        }
        $appointment = new Appointment();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $search = '';

        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }

        $appointment = $appointment->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($q) {
                $q->whereNotNull('zf_fee');
            })->whereBetween('appointment_date', [$from_date, $to_date]);

        if ($search) {
            $appointment = $appointment->where(function ($query) use ($search) {
                $query->where('appointment_date', 'like', '%' . $search . '%')
                    ->orWhere('appointment_time', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhereHas('doctor', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $appointment = $appointment->get();

        return Inertia::render('Donation/Reports/zf', [
            'reports' => $appointment,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
            'search' => $search,
        ]);
    }


    public function CareOFFReport()
    {
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date_obj = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date_obj) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date_obj->format('Y-m-d H:i');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date_obj = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date_obj) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date_obj->format('Y-m-d H:i');
        }

        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        $searchTerm = request()->get('search', default: null);

    if(file_exists(base_path('config/hrms.php'))) {
        $appointmentsQuery = Appointment::with(['careoff', 'patient', 'appointmentDetails', 'doctor'])
            ->where(function ($query) {
                $query->whereNotNull('careoff_id')
                    ->orWhereNotNull('zf_fee');
            })
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->where(function ($q) use ($from_date_only, $from_time_only) {
                    $q->where('appointment_date', '>', $from_date_only)
                    ->orWhere(function ($q2) use ($from_date_only, $from_time_only) {
                        $q2->where('appointment_date', '=', $from_date_only)
                            ->where('appointment_time', '>=', $from_time_only);
                    });
                })->where(function ($q) use ($to_date_only, $to_time_only) {
                    $q->where('appointment_date', '<', $to_date_only)
                    ->orWhere(function ($q2) use ($to_date_only, $to_time_only) {
                        $q2->where('appointment_date', '=', $to_date_only)
                            ->where('appointment_time', '<=', $to_time_only);
                    });
                });
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc');
    }
    if(file_exists(base_path('config/hrms.php'))) {

        $admissionsQuery = Admission::with(['careoff', 'patient', 'details', 'details.service:id,name', 'details.doctor:id,name'])
            ->where(function ($query) {
                $query->whereNotNull('careoff_id')
                    ->orWhereNotNull('zf_fee');
            })
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->where(function ($q) use ($from_date_only, $from_time_only) {
                    $q->where('discharge_date', '>', $from_date_only)
                    ->orWhere(function ($q2) use ($from_date_only, $from_time_only) {
                        $q2->where('discharge_date', '=', $from_date_only)
                            ->where('discharge_time', '>=', $from_time_only);
                    });
                })->where(function ($q) use ($to_date_only, $to_time_only) {
                    $q->where('discharge_date', '<', $to_date_only)
                    ->orWhere(function ($q2) use ($to_date_only, $to_time_only) {
                        $q2->where('discharge_date', '=', $to_date_only)
                            ->where('discharge_time', '<=', $to_time_only);
                    });
                });
            })
            ->orderBy('discharge_date', 'asc')
            ->orderBy('discharge_time', 'asc');
        }
    if(file_exists(base_path('config/lab.php'))) {
        $patientTestsQuery = PatientTest::with([
            'careoff',
            'patient',
            'patientTestDetails.package',
            'patientTestDetails.testGeneralDetails.subPackage',
            'patientTestDetails.testGeneralDetails.labTest',
            'patientTestDetails.testGeneralDetails.testCategory',
            'testWidalDetails.widalTest',
            'testCrossMatchDetails'
        ])
            ->where(function ($query) {
                $query->whereNotNull('careoff_id')
                    ->orWhereNotNull('zf_fee');
            })
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->where(function ($q) use ($from_date_only, $from_time_only) {
                    $q->where('test_date', '>', $from_date_only)
                    ->orWhere(function ($q2) use ($from_date_only, $from_time_only) {
                        $q2->where('test_date', '=', $from_date_only)
                            ->where('test_time', '>=', $from_time_only);
                    });
                })->where(function ($q) use ($to_date_only, $to_time_only) {
                    $q->where('test_date', '<', $to_date_only)
                    ->orWhere(function ($q2) use ($to_date_only, $to_time_only) {
                        $q2->where('test_date', '=', $to_date_only)
                            ->where('test_time', '<=', $to_time_only);
                    });
                });
            })
            ->orderBy('test_date', 'asc')
            ->orderBy('test_time', 'asc');
    }

        if ($searchTerm) {
            if(file_exists(base_path('config/hrms.php'))) {
                $appointmentsQuery->where(function ($query) use ($searchTerm) {
                    $query->where('id', $searchTerm)
                        ->orWhere('patient_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('careoff', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                        });
                });
            }

            if(file_exists(base_path('config/hrms.php'))) {

            $admissionsQuery->where(function ($query) use ($searchTerm) {
                $query->where('id', $searchTerm)
                    ->orWhere('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('careoff', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
            }

            if(file_exists(base_path('config/lab.php'))) {

            $patientTestsQuery->where(function ($query) use ($searchTerm) {
                $query->where('id', $searchTerm)
                    ->orWhere('patient_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('careoff', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
            }
        }

        if(file_exists(base_path('config/hrms.php'))) {
            $appointments = $appointmentsQuery->get();
        }
        if(file_exists(base_path('config/hrms.php'))) {
            $admissions = $admissionsQuery->get();
        }
        if(file_exists(base_path('config/lab.php'))) {
            $patientTests = $patientTestsQuery->get();
        }

        $combinedResults = collect();

        if(file_exists(base_path('config/hrms.php'))) {
            foreach ($appointments as $appointment) {
                $combinedResults->push([
                    'type' => 'Appointment',
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient_name,
                    'careoff_name' => $appointment->careoff->name ?? 'N/A',
                    'date' => $appointment->appointment_date,
                    'doctor' => $appointment->doctor->name ?? '',
                    'donor_fee' => $appointment->donor_fee,
                    'zf_fee' => $appointment->zf_fee,
                ]);
            }
        }

        if(file_exists(base_path('config/hrms.php'))) {
            foreach ($admissions as $admission) {
                $doctors = $admission->details->pluck('doctor.name')->filter()->join(', ');
                $services = $admission->details->pluck('service.name')->filter()->join(', ');

                $combinedResults->push([
                    'type' => 'Admission',
                    'id' => $admission->id,
                    'patient_name' => $admission->name,
                    'careoff_name' => $admission->careoff->name ?? '',
                    'date' => $admission->discharge_date,
                    'donor_fee' => $admission->donor_fee,
                    'zf_fee' => $admission->zf_fee,
                    'doctor' => $doctors,
                    'service' => $services,
                ]);
            }
        }

        if(file_exists(base_path('config/lab.php'))) {
            foreach ($patientTests as $patientTest) {
                $combinedResults->push([
                    'type' => 'Patient Test',
                    'id' => $patientTest->id,
                    'patient_name' => $patientTest->patient_name,
                    'careoff_name' => $patientTest->careoff->name ?? '',
                    'date' => $patientTest->test_date,
                    'donor_fee' => $patientTest->donor_fee,
                    'zf_fee' => $patientTest->zf_fee,
                    'patient_test_details' => $patientTest->patientTestDetails,
                ]);
            }
        }

        return Inertia::render('Donation/Reports/CareOFFReport', [
            'reports' => $combinedResults,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'searchTerm' => $searchTerm,
        ]);
    }

}
