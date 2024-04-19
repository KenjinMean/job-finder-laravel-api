<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchJobPostingsRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'load' => 'nullable|array',

            'keyword' => "nullable|string",
            'work_location_type' => 'nullable|array',
            'job_type' => 'nullable|array',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'skills' => 'nullable|array',

            'per_page' => 'nullable|integer|min:1|max:100',
            'order_by' => "nullable|string",
            'order_direction' => "nullable|string"
        ];
    }
}
