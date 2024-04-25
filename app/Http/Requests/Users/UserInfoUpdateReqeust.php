<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoUpdateReqeust extends FormRequest {
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
            "first_name" => "required_if:present,first_name|string",
            "last_name" => "required_if:present,last_name|string",
            "headline" => "string|nullable",
            "additional_name" => "string|nullable",
            "about" => "string|nullable",
        ];
    }
}
