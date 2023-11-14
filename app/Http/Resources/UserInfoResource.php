<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'headline' => $this->headline,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'additional_name' => $this->additionalName,
            'about' => $this->about,
            'profile_image' => $this->profile_image,
            'cover_image' => $this->cover_image,
            'resume' => $this->resume,
            'birth_date' => $this->birthday,
        ];
    }
}
