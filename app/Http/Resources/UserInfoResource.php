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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'additional_name' => $this->additional_name,
            'about' => $this->about,
            'profile_image' => $this->profile_image,
            'cover_image' => $this->cover_image,
            'resume' => $this->resume,
        ];
    }
}
