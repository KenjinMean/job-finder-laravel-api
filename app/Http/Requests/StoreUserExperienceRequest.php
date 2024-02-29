<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserExperienceRequest extends FormRequest {
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
            'id' => 'nullable|string',
            'company_name' => 'required|string',
            'job_title' => 'required|string',
            'position' => 'required|string',
            'job_type' => 'required|string',
            'work_location_type' => 'required|string',
            'location' => 'required|string',
            'is_current' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => $this->isEndDateNullable(),
            'skillIds' => 'nullable|array',
        ];
    }

    /**
     * convert true or false from javascript to PHP boolean true/false or 1/0
     * 
     */
    public function prepareForValidation() {
        $this->merge([
            'is_current' => $this->is_current === 'true' ? true : false,
            'end_date' => $this->is_current === 'true' ? null : $this->end_date,
        ]);
    }

    /**
     * Get the validation rule for the end_date field based on the value of is_current.
     *
     * @return string
     */
    protected function isEndDateNullable(): string {
        return $this->is_current ? 'nullable|date' : 'required|date|after:start_date';
    }
}
