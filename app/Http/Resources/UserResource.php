<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'email' => $this->email,
            'user_info' => [
                'first_name' => $this->userInfo->firstName,
                'profile_image' => $this->userInfo->profile_image,
            ],
        ];
    }
}
