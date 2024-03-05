<?php

namespace App\Services;

use App\Models\UserEducation;
use App\Http\Resources\UserEducationResource;

class UserEducationService {

  /* ----------------------------------------------------------- */
  public function getUserEducations($user) {
    $userEducation = $user->userEducations;
    return UserEducationResource::collection($userEducation);
  }

  /* ----------------------------------------------------------- */
  public function getUserEducation($user, $educationId) {
    $userEducation = UserEducation::where('user_id', $user->id)->find($educationId);
    return new UserEducationResource($userEducation);
  }

  /* ----------------------------------------------------------- */
  public function createUserEducation($data, $user) {
    $data['user_id'] = $user->id;
    UserEducation::create($data);
  }

  /* ----------------------------------------------------------- */
  public function updateUserEducation($data, $id) {
    $userEducation = UserEducation::findOrFail($id);
    $userEducation->update($data);
  }

  /* ----------------------------------------------------------- */
  public function destroyUserEducation($educationId) {
    $userEducation = UserEducation::findOrFail($educationId);
    $userEducation->delete();
  }
}
