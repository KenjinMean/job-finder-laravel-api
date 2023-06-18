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
            'id' => $this->id,
            'name' => $this->name,
            'company_logo' => $this->company_logo,
            'location' => $this->location,
            'website' => $this->website,
            'description' => $this->description,
            'industry' => $this->industry,
        ];
    }
}
