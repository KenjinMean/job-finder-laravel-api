<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\User;

class RefreshTokenGenerator {
  public static function generateRefreshToken(User $user) {
    $refreshToken = Str::random(60);
    $user->update(['refresh_token' => hash('sha256', $refreshToken)]);

    return $refreshToken;
  }
}
