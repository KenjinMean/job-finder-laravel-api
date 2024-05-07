<?php

namespace App\Http\Requests;

use App\Models\JobType;
use Illuminate\Support\Facades\DB;
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

            'title' => "nullable|string",
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'location' => "nullable|string",
            'company' => "nullable|string",

            'skills' => 'nullable|array',
            'job_type' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $existingJobTypes = DB::table('job_types')->pluck('id', 'job_type')->toArray();
                    foreach ($value as $item) {
                        if (!isset($existingJobTypes[$item]) && !in_array($item, $existingJobTypes)) {
                            $fail("The selected $attribute is invalid.");
                        }
                    }
                },
            ],
            'work_location_type' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $existingWorkLocationTypes = DB::table('work_location_types')->pluck('id', 'name')->toArray();
                    foreach ($value as $item) {
                        if (!isset($existingWorkLocationTypes[$item]) && !in_array($item, $existingWorkLocationTypes)) {
                            $fail("The selected $attribute is invalid.");
                        }
                    }
                },
            ],

            'load' => 'nullable|array|in:skills,company,jobTypes,workLocationTypes',

            'per_page' => 'nullable|integer|min:1|max:100',
            'order_by' => "nullable|string",
            'order_direction' => "nullable|string"
        ];
    }
}
