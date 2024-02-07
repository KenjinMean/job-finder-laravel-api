<?php

namespace App\Services;

use App\Http\Resources\UserEducationResource;

class UserEducationService {
  public function getUserEducation($user) {
    $userEducation = $user->userEducations;

    return UserEducationResource::collection($userEducation);
  }
}
