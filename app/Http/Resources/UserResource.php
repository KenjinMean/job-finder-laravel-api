<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'user_info' => [
                'profile_image' => $this->userInfo->profile_image,
                'first_name' => $this->userInfo->firstName,
                'last_name' => $this->userInfo->lastName,
            ],
        ];
    }
}
