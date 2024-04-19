<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest {
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
        $rules = [
            'company_id' => 'required|integer',
            'title' => 'required|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'salary' => 'nullable|integer',
            'benefits' => 'nullable|string',
            'experience_level' => 'nullable|string',
            'category' => 'nullable|string',
            'application_deadline_at' => 'nullable|date',
            'skills' => 'nullable|array',
            'job_types' => 'nullable|array',
            'job_types.*' => 'exists:job_types,id',
            'work_location_types' => 'nullable|array',
            'work_location_types.*' => 'exists:work_location_types,id',
        ];

        if ($this->isMethod('PATCH')) {
            // Exclude 'company_id' rule for PATCH requests
            unset($rules['company_id']);

            $rules['title'] = 'sometimes|required|string|min:1';
        }

        return $rules;
    }
}
