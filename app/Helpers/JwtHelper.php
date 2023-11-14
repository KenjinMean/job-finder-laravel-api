<?php
// app\Helpers\JwtHelper.php

namespace App\Helpers;

use App\Http\Resources\LoginResource;
use App\Models\User;
use App\Services\RefreshTokenGenerator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtHelper {
  public static function getUserFromToken() {
    try {
      $token = JWTAuth::getToken();
      return JWTAuth::toUser($token);
    } catch (\Throwable $e) {
      return null;
    }
  }

  public static function generateAccessToken($user) {
    $token = JWTAuth::fromUser($user);
    $user->load('userInfo');
    $userResource = new LoginResource($user);
    $expiresInMinutes = JWTAuth::factory()->getTTL() * 60;

    return response()->json([
      'user' => $userResource,
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => $expiresInMinutes,
    ]);
  }

  public static function refreshAccessToken() {
    $token = JWTAuth::parseToken()->refresh();
    $expiresInMinutes = JWTAuth::factory()->getTTL() * 60;

    return response()->json([
      'access_token' => $token,
      'expires_in' => $expiresInMinutes,
    ]);
  }

  public static function generateAccessTokenAndSetRefreshCookie(User $user) {
    $token = JWTAuth::fromUser($user);
    $refreshToken = RefreshTokenGenerator::generateRefreshToken($user);
    $user->load('userInfo');
    $userResource = new LoginResource($user);
    $expiresInMinutes = 30 * 24 * 60; // 30 days

    return response()->json([
      'user' => $userResource,
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60,
    ])
      ->withCookie(cookie('refresh_token', $refreshToken, $expiresInMinutes));
  }
}
