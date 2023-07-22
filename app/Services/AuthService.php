<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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

    # SANCTUM token generator
    // Auth::login($user);
    // $user = User::with('userInfo')->find(Auth::id());
    // $token = $user->createToken('main')->plainTextToken;
    // return response(compact('user', 'token'));

    # This line is sending email verification
    event(new Registered($user));

    $user = Auth::user();

    # JWT token generate
    $token = JWTAuth::fromUser($user);
    $user = User::with('userInfo')->find(Auth::id());
    return response(compact('user', 'token'));
  }

  public function login($validatedCredentials) {

    $user = Auth::attempt($validatedCredentials);
    if (!$user) {
      return response([
        'message' => 'provided email or password is incorrect'
      ], 422);
    }

    $user = Auth::user();
    $token = JWTAuth::fromUser($user);
    $user = User::with('userInfo')->find(Auth::id());
    return response(compact('user', 'token'));
  }

  public function logout($request) {
    JWTAuth::invalidate();
    // auth()->logout();
  }
}
