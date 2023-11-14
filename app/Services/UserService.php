<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\JwtHelper;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserService {
  public function createUser($validatedRequest) {
    $user = User::create($validatedRequest);
    return $user;
  }

  public function getUser($user) {
    $user->load('skills');
    // return new UserResource(Auth::user());
    // return new UserResource($user);
    return new UserProfileResource($user->load('skills', 'userInfo'));
  }

  public function updateUser($user, $validatedRequest) {
    $updateData = [];

    if (!empty($validatedRequest['email'])) {
      $updateData['email'] = $validatedRequest['email'];
    }

    if (!empty($validatedRequest['password'])) {
      $updateData['password'] = $validatedRequest['password'];
    }

    $user->update($updateData);
  }

  public function deleteUser($user): void {
    $user->delete();
    $token = JWTAuth::getToken();
    JWTAuth::invalidate($token);
  }

  public function updateSkill($user, $skills) {
    /** @var User $user */
    $user->skills()->sync($skills);
  }
}
