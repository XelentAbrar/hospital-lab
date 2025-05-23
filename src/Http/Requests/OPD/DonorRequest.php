<?php

namespace XelentAbrar\HospitalDonation\Http\Requests\Donation;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;

class DonorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:care_off,zf,donation',
            'description' => 'nullable|string',
            'address' => 'nullable|numeric|min:0',
            'coa_id' => 'required|exists:'. ChartOfAccount::class .',id',
        ];

        return $rules;
    }
}
