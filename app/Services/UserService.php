<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserService {
  public function createUser($validatedRequest) {
    $user = User::create($validatedRequest);
    return $user;
  }

  public function getUser() {
    return new UserResource(Auth::user());
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
    Auth::logout();
  }

  public function updateSkill($user, $skills) {
    /** @var User $user */
    $user->skills()->sync($skills);
  }
}
