<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserContactResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'phone' => $this->phone,
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
            "birth_date" => $this->birth_date,
        ];
    }
}
