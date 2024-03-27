<?php
// UNUSED
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
            'is_email_verified' => $this->email_verified_at !== null,
        ];
    }
}
