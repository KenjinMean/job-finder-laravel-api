<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company_id' => $this->company_id,
            'location' => $this->location,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'salary' => $this->salary,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
