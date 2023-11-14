<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'user_info' => new UserInfoResource($this->whenLoaded('userInfo')),
            'user_skills' => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
