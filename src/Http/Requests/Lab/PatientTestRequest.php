<?php

namespace App\Http\Requests\Lab;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use XelentAbrar\HospitalLab\Models\Donation\Donor;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use App\Models\OPD\Patient;
use XelentAbrar\HospitalLab\Models\Lab\WidalTest;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use App\Rules\DiscountLessThanTotal;
use Illuminate\Foundation\Http\FormRequest;

class PatientTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => 'nullable',
            'test_date' => 'nullable|date|before_or_equal:today',
            'lab_no' => 'nullable',
            'lab_serial' => 'nullable',
            'test_time' => 'nullable|date_format:H:i',
            'test_nature' => 'required|in:General,Widal,Cross_Match,Other_Receipt',
            'test_type' => 'required|in:IPD,OPD,Private',
            'file_no' => 'nullable|string|max:255',
            'status' => 'nullable|in:Ordered,In_Progress,Completed,Cancelled,Critical',
            'referred_by' => 'required|string',
            'patient_id' => 'nullable|exists:' . Patient::class . ',id',
            'patient_name' => 'required|string|max:255',
            'patient_cnic' => 'nullable|string|max:255',
            'patient_age' => 'required',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'patient_dob' => 'nullable|date|before_or_equal:today',
            'patient_gender' => 'nullable|in:Male,Female,Children',
            'patient_relation_name' => 'nullable|string|max:255',
            'patient_relation_type' => 'nullable|in:Guardian,Spouse,Parent,Sibling,Child,Friend,Relative',
            'welfare' => 'nullable|string|max:255',
            'patient_address' => 'nullable|string|max:255',
            'patient_country_id' => 'nullable|exists:' . Country::class . ',id',
            'patient_state_id' => 'nullable|exists:' . State::class . ',id',
            'patient_city_id' => 'nullable|exists:' . City::class . ',id',
            'patient_phone' => 'required|string|max:255',
            'patient_email' => 'nullable|email|max:255',
            'patient_blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'patient_rh_factor' => 'nullable|string|max:255',
            'specimen' => 'nullable|string|max:255',
            'date_sample' => 'nullable|string',
            'date_report_ready' => 'nullable|string',
            'date_report_collection' => 'nullable|string',
            'free_test' => 'nullable|boolean',
            'discount_type' => 'nullable|in:Fixed,Percentage',
            'discount_value' => 'nullable|numeric',
            'total_amount' => 'required|numeric|min:1',
            'date_report_verification' => 'nullable|string',
            'remarks' => 'nullable',
            'chart_no' => 'nullable',
        ];
        if(file_exists(base_path('config/donation.php'))) {
            $rules['donor_fee'] = ['nullable|string|max:255'];
            $rules['zf_fee'] = ['nullable|string|max:255'];
            $rules['careoff_id'] = ['nullable|exists:' . Donor::class . ',id'];
            $rules['zf_id'] = ['nullable|exists:' . Donor::class . ',id'];
        }

        if ($this->test_nature === 'General' || $this->test_nature === 'Other_Receipt' ) {
            $rules['patient_test_details'] = ['required', 'array', 'min:1'];
            $rules['patient_test_details.*.package_id'] = ['nullable', 'exists:packages,id'];
            $rules['patient_test_details.*.patient_test_id'] = ['nullable', 'exists:patient_tests,id'];
            $rules['patient_test_details.*.remarks'] = ['nullable'];
            $rules['patient_test_details.*.selectedPackage.id'] = ['nullable', 'exists:packages,id'];
            $rules['patient_test_details.*.selectedPackage.name'] = ['nullable', 'string'];
            $rules['patient_test_details.*.selectedPackage.price'] = ['nullable', 'numeric'];
            $rules['patient_test_details.*.test_general_details'] = ['nullable', 'array'];
            $rules['patient_test_details.*.test_general_details.*.lab_test.id'] = ['nullable', 'exists:' . LabTest::class . ',id'];
            $rules['patient_test_details.*.test_general_details.*.sub_pacakge.id'] = ['nullable', 'exists:' . SubPackage::class . ',id'];
            $rules['patient_test_details.*.test_general_details.*.lab_test_detail.id'] = ['nullable', 'exists:' . LabTestDetail::class . ',id'];
            $rules['patient_test_details.*.test_general_details.*.result'] = ['nullable', 'string'];
            $rules['patient_test_details.*.test_general_details.*.result_2'] = ['nullable'];
            $rules['patient_test_details.*.test_general_details.*.lab_serial'] = ['nullable'];
            $rules['patient_test_details.*.test_general_details.*.description'] = ['nullable', 'string'];
        } elseif ($this->test_nature === 'Widal') {
            $rules['test_widal_details'] = ['required', 'array', 'min:1'];
            $rules['test_widal_details.*.widal_test.id'] = ['required', 'exists:' . WidalTest::class . ',id'];
            $rules['test_widal_details.*.1_40'] = ['nullable', 'string'];
            $rules['test_widal_details.*.1_80'] = ['nullable', 'string'];
            $rules['test_widal_details.*.1_160'] = ['nullable', 'string'];
            $rules['test_widal_details.*.1_320'] = ['nullable', 'string'];
            $rules['test_widal_details.*.1_640'] = ['nullable', 'string'];
        } elseif ($this->test_nature === 'Cross_Match') {
            $rules['test_cross_match_details.donor_name'] = ['required', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_blood_group'] = ['required', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'];
            $rules['test_cross_match_details.donor_rh_factor'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_age'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_blood_pressure'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_anti_hcv'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_hbsag'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_hiv_antibodies'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.donor_cross_match'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.blood_bag_no'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.blood_component'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.blood_volumn'] = ['nullable', 'string', 'max:255'];
            $rules['test_cross_match_details.bleeding_date'] = ['nullable', 'string'];
            $rules['test_cross_match_details.bag_expiry_date'] = ['nullable', 'string'];
        }

        if ($this->discount_type === 'Percentage') {
            $rules['discount_value'] = ['nullable', 'numeric', 'min:0', 'max:100'];
        } elseif ($this->discount_type === 'Fixed') {
            $rules['discount_value'] = ['nullable', 'numeric', 'min:0', new DiscountLessThanTotal($this->input('total_amount'))];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'patient_id' => 'patient',
            'country_id' => 'country name',
            'state_id' => 'state name',
            'city_id' => 'city name',
            'dob' => 'date of birth',
            'test_general_details.*.lab_test.id' => 'lab test',
            'test_general_details.*.result' => 'result',
            'test_general_details.*.result_2' => 'result 2',
            'test_general_details.*.lab_test_detail.id' => 'lab test detail',
            'test_general_details.*.description' => 'description',
            'test_cross_match_details.donor_name' => 'donor name',
            'test_cross_match_details.donor_blood_group' => 'blood group',
            'test_cross_match_details.donor_rh_factor' => 'RH factor',
            'test_cross_match_details.donor_age' => 'age',
            'test_cross_match_details.donor_blood_pressure' => 'blood pressure',
            'test_cross_match_details.donor_anti_hcv' => 'anti HCV',
            'test_cross_match_details.donor_hbsag' => 'hbsag',
            'test_cross_match_details.donor_hiv_antibodies' => 'HIV antibodies',
            'test_cross_match_details.donor_cross_match' => 'cross match',
            'test_cross_match_details.blood_bag_no' => 'bag no',
            'test_cross_match_details.blood_component' => 'blood component',
            'test_cross_match_details.blood_volumn' => 'volume',
            'test_cross_match_details.bleeding_date' => 'bleeding date',
            'test_cross_match_details.bag_expiry_date' => 'expiry date',
        ];
    }
}
