<?php

namespace XelentAbrar\HospitalDonation\Http\Requests\Donation;

use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use Illuminate\Foundation\Http\FormRequest;

class DonorFundRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'donor_id' => 'required|exists:' . Donor::class . ',id',
            'charges' => 'required|numeric|min:0',
            'account_on' => 'nullable',
            'donor_serial' => 'nullable',
            'donor_date' => 'nullable|date|before_or_equal:today',
            'narration' => 'nullable',
            'donor_time' => 'nullable|date_format:H:i',
        ];

        return $rules;
    }
}
