<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEducationResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'institution_name' => $this->institution_name,
            'degree' => $this->degree,
            'major' => $this->major,
            'start_date' => $this->start_date,
            "end_date" => $this->end_date,
            "graduation_status" => $this->graduation_status,
        ];
    }
}
