<?php

namespace App\Services;

use App\Http\Resources\UserExperienceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserExperienceService {

  public function showUserExperience($user): AnonymousResourceCollection {
    $userExperiences = $user->userWorkExperiences;

    return UserExperienceResource::collection($userExperiences);
  }
}
