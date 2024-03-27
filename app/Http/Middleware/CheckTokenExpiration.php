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
            return ResponseHelper::errorResponse('Token has expired', Response::HTTP_UNAUTHORIZED, $e->getMessage(), __FILE__);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('Failed to authenticate token', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), __FILE__);
        }
    }
}
