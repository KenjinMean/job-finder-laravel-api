<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserEducationRequest extends FormRequest {
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
            'institution_name' => 'required|string',
            'degree' => 'nullable|string',
            'major' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }
}
