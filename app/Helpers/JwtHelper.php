<?php
// app\Helpers\JwtHelper.php

namespace App\Helpers;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtHelper {
  public static function getUserFromToken() {
    try {
      $token = JWTAuth::getToken();
      return JWTAuth::toUser($token);
    } catch (\Throwable $e) {
      // Handle any exceptions that might occur during the process
      return null;
    }
  }
}
