<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;

class AuthService {
  public function register($validatedRequest) {
    $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
    $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

    $user = User::create($validatedRequest);
    UserInfo::create([
      'firstName' => $validatedRequest['name'],
      'user_id' => $user->id,
      'profile_image' => $defaultProfileImagePath,
      'cover_image' => $defaultCoverImagePath,
    ]);
    Auth::login($user);
    $user = User::with('userInfo')->find(Auth::id());
    $token = $user->createToken('main')->plainTextToken;
    return response(compact('user', 'token'));
  }

  public function login($validatedCredentials) {
    if (!Auth::attempt($validatedCredentials)) {
      return response([
        'message' => 'provided email or password is incorrect'
      ], 422);
    }
    /** @var User $user */
    // $user = Auth::user();
    $user = User::with('userInfo')->find(Auth::id());
    $token = $user->createToken('main')->plainTextToken;
    return response(compact('user', 'token'));
  }

  public function logout($request) {
    $request->user()->currentAccessToken()->delete();
  }
}
