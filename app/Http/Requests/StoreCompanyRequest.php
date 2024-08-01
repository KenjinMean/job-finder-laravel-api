<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest {
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
            'name' => 'required|nullable',
            'website'  => 'string|nullable',
            'location'  => 'string|nullable',
            'description'  => 'string|nullable',
            'company_logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email' => 'string|nullable',
            'phone' => 'string|nullable',
            'company_size_id' => 'integer|nullable',
        ];
    }
}
