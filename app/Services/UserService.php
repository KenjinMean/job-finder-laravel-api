<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserService {
  public function index() {
  }

  // |--------------------------------------------------------------------------
  public function store($validatedRequest) {
    $user = User::create($validatedRequest);
    return $user;
  }

  // |--------------------------------------------------------------------------
  public function show($user) {
    return new UserResource($user);
  }

  // |--------------------------------------------------------------------------
  // To IMPLEMENT: hard to overcome when not using https
  public function updateUserEmail($user, $validatedRequest) {
    $user->email = $validatedRequest['email'];
    $user->save();
  }

  // |--------------------------------------------------------------------------
  public function updateUserPassword($user, $validatedRequest) {
    if (!Hash::check($validatedRequest['current_password'], $user->password)) {
      throw new UnauthorizedHttpException("", "Current password is incorrect.");
    }

    $user->password = bcrypt($validatedRequest['password']);
    $user->save();
  }

  // |--------------------------------------------------------------------------
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

  // |--------------------------------------------------------------------------
  public function deleteUser($user): void {
    // delete user profile and cover and all related to user on the storage
    $user->delete();
    $token = JWTAuth::getToken();
    JWTAuth::invalidate($token);
  }

  // |--------------------------------------------------------------------------
  public function updateSkill($user, $skills) {
    /** @var User $user */
    $user->skills()->sync($skills);
  }
}
