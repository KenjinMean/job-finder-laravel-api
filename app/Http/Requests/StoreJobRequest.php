<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest {
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
            'company_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
            'requirements' => 'required',
            'salary' => 'required',
        ];
    }
}
