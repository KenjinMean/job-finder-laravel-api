<?php

namespace App\Services;

use App\Http\Resources\UserEducationResource;
use App\Models\UserEducation;

class UserEducationService {

  /* ----------------------------------------------------------- */
  public function index($user) {
    $userEducation = $user->userEducations;
    return UserEducationResource::collection($userEducation);
  }

  /* ----------------------------------------------------------- */
  public function createUserEducation($data, $user) {
    $data['user_id'] = $user->id;
    UserEducation::create($data);
  }

  /* ----------------------------------------------------------- */
  public function updateUserEducation($data, $id) {
    $userEducation = UserEducation::findOrFail($id);

    // return response()->json(["user-education" => $userEducation]);
    $userEducation->update($data);
  }

  /* ----------------------------------------------------------- */
  public function destroyUserEducation($userEducationId) {
    $userEducation = UserEducation::findOrFail($userEducationId);
    $userEducation->delete();
  }
}
