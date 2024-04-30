<?php

namespace App\Http\Resources\users;

use Illuminate\Http\Request;
use App\Http\Resources\JobTypeResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\WorkLocationTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCompanyJobResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'location' => $this->location,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'responsibilities' => $this->responsibilities,
            'salary' => $this->salary,
            'benefits' => $this->benefits,
            'experience_level' => $this->experience_level,
            'category' => $this->category,
            'application_deadline_at' => $this->application_deadline_at,
            'company_id' => $this->company_id,

            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'job_types' => JobTypeResource::collection($this->whenLoaded('jobTypes')),
            'work_location_types' => WorkLocationTypeResource::collection($this->whenLoaded(('workLocationTypes'))),
        ];
    }
}
