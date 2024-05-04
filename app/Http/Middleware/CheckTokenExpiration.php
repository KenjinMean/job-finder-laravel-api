<?php

namespace App\Http\Middleware;

use Closure;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckTokenExpiration {
    public function handle($request, Closure $next) {
        $token = JWTAuth::getToken();
        JWTAuth::checkOrFail($token);
        return $next($request);
    }
}
