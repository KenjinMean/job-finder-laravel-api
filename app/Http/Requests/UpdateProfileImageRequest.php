<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileImageRequest extends FormRequest {
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
            'profile_image' => 'required|image|max:2040',
        ];
    }

    public function messages(): array {
        return [
            'profile_image.required' => 'The profile image is required.',
            'profile_image.image' => 'The profile image must be a valid image file.',
            'profile_image.max' => 'The profile image may not be greater than 2MB in size.',
        ];
    }
}
