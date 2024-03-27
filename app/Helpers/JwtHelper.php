<?php
// app\Helpers\JwtHelper.php

namespace App\Helpers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Services\RefreshTokenGenerator;
use App\Http\Resources\UserInfoResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtHelper {
  const DEFAULT_TTL_MINUTES = 60;
  // param minutes
  public static function getAccessTokenExpiration($minutes = self::DEFAULT_TTL_MINUTES) {
    return JWTAuth::factory()->getTTL() * $minutes;
  }

  public static function getUserFromToken() {
    try {
      $token = JWTAuth::getToken();
      return JWTAuth::toUser($token);
    } catch (\Throwable $e) {
      return null;
    }
  }

  public static function generateAccessToken($user) {
    return response()->json([
      'user' => new UserResource($user),
      'user_info' =>  new UserInfoResource($user->userInfo),
      'access_token' => JWTAuth::fromUser($user),
      'token_type' => 'bearer',
      'expires_in' => self::getAccessTokenExpiration(),
    ]);
  }

  public static function refreshAccessToken() {
    return response()->json([
      'access_token' => JWTAuth::parseToken()->refresh(),
      'expires_in' =>  self::getAccessTokenExpiration(),
    ]);
  }

  // implement this if already have access to "https" site
  public static function generateAccessTokenAndSetRefreshCookie(User $user) {
    $token = JWTAuth::fromUser($user);
    $refreshToken = RefreshTokenGenerator::generateRefreshToken($user);
    $user->load('userInfo');
    $expiresInMinutes = 30 * 24 * 60; // 30 days

    return response()->json([
      'user' => new UserResource($user),
      'user_info' =>  new UserInfoResource($user->userInfo),
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => self::getAccessTokenExpiration(),
    ])
      ->withCookie(cookie('refresh_token', $refreshToken, $expiresInMinutes));
  }
}
