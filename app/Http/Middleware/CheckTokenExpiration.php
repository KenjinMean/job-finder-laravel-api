<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;

class CheckTokenExpiration {
    public function handle($request, Closure $next) {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::checkOrFail($token);
            return $next($request);
        } catch (TokenExpiredException $e) {
            return ResponseHelper::generateErrorResponse($e, "jwt error", Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return ResponseHelper::generateErrorResponse($e, "jwt error", Response::HTTP_UNAUTHORIZED);
        }
    }
}
