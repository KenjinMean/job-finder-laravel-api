<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest {
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
            "firstName" => "string|nullable",
            "lastName" => "string|nullable",
            "headline" => "string|nullable",
            "additionalName" => "string|nullable",
            "pronouns" => "string|nullable",
            "about" => "string|nullable",
            "location" => "string|nullable",
        ];
    }
}
