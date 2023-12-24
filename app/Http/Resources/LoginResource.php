<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'user_info' => [
                'profile_image' => $this->userInfo->profile_image,
                'first_name' => $this->userInfo->first_name,
                'last_name' => $this->userInfo->last_name,
                'headline' => $this->userinfo->headline,
            ],
        ];
    }
}
