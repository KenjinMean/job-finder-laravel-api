<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'name' => $this->title,
            'location' => $this->location,
            'website' => $this->description,
            'description' => $this->requirements,
            'industry' => $this->salary,
        ];
    }
}
