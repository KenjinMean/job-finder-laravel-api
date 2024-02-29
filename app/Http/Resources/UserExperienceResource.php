<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExperienceResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'position' => $this->position,
            'job_type' => $this->job_type,
            'work_location_type' => $this->work_location_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'is_current' => $this->is_current,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
