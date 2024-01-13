<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserContactRequest extends FormRequest {
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
            'phone' => ['nullable', 'regex:/^\+63-\d{3}-\d{3}-\d{4}$/'],
            'city' => "string|nullable",
            'province' => "string|nullable",
            'country' => "string|nullable",
            'zip_code' => "string|nullable",
            "birth_date" => "nullable|date|date_format:Y-m-d",
        ];
    }
}
