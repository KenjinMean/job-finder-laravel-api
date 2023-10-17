<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\SkillResource;
use App\Http\Resources\CompanyLogoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailsResource extends JsonResource {
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
      'description' => $this->description,
      'location' => $this->location,
      'salary' => $this->salary,
      'requirements' => $this->requirements,
      'created_at' => $this->created_at->format('Y-m-d H:i:s'),
      'company' => new CompanyResource($this->whenLoaded('company')),
      'skills' => SkillResource::collection($this->whenLoaded('skills')),
      'job_types' => JobTypeResource::collection($this->whenLoaded('jobTypes')),
    ];
  }
}
