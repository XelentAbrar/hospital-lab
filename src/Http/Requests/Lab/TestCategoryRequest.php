<?php

namespace App\Http\Requests\Lab;

use XelentAbrar\HospitalLab\Models\Lab\TestGroup;
use Illuminate\Foundation\Http\FormRequest;

class TestCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'test_group_id' => 'required|exists:' . TestGroup::class . ',id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'test_group_id' => 'test group',
        ];
    }
}
