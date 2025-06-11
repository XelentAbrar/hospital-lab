<?php

namespace App\Http\Requests\Lab;

use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'test_category_id' => 'required|exists:' . TestCategory::class . ',id',
            'status' => 'required|in:active,inactive',
            'price' => 'required|numeric|min:1',
            'lab_tests' => ['required', 'array', 'min:1'],
            'lab_tests.*' => ['required', 'exists:' . SubPackage::class . ',id'],
        ];

        return $rules;
    }
}
