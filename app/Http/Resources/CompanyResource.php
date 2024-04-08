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
            'website' => $this->website,
            'location' => $this->location,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_size' => $this->companySizeCategory->size,
            'company_logo' => $this->company_logo,
        ];
    }
}
