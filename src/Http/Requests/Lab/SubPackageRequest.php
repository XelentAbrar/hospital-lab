<?php

namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class SubPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'price' => 'nullable|numeric|min:1',
            'lab_tests' => ['required', 'array', 'min:1'],
            'lab_tests.*' => ['required', 'exists:lab_tests,id'],
        ];

        return $rules;
    }
}
