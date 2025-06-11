<?php

namespace App\Http\Requests\Lab;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalLab\Models\Lab\Unit;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use Illuminate\Foundation\Http\FormRequest;

class LabTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'test_category_id' => 'required|exists:' . TestCategory::class . ',id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'lab_test_type' => 'nullable',
            'price' => 'required|numeric|min:0',
            'sample_required' => 'nullable|string',
            'reporting_time' => 'nullable|string',
            'min_value' => 'nullable|string',
            'max_value' => 'nullable|string',
            'value_range' => 'nullable|string|max:255',
            'lab_type' => 'required|string|max:255',
            'unit_id' => 'nullable|exists:' . Unit::class . ',id',
            'status' => 'required|in:active,inactive',
            'coa_id' => 'nullable|exists:'. ChartOfAccount::class .',id',
            'details' => 'array',
            'details.*.test_report' => 'nullable|string',
            'details.*.id' => 'nullable|exists:' . LabTestDetail::class . ',id',
            'details.*.lab_test_id' => 'nullable|exists:' . LabTest::class . ',id',


        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'test_category_id' => 'test category',
            'unit_id' => 'unit',
        ];
    }
}
