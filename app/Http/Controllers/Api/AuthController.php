<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Exceptions\AccountConflictException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller {

    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    // |--------------------------------------------------------------------------
    public function login(LoginRequest $request) {
        $AuthenticatedUserData = $this->authService->login($request);

        return response()->json($AuthenticatedUserData, Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function register(RegisterRequest $request) {
        $AuthenticatedUserData = $this->authService->register($request);

        return response()->json($AuthenticatedUserData, Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    public function logout() {
        $user = JwtHelper::getUserFromToken();
        $this->authService->logout($user);

        return response(['message' => 'User logged out successfully.'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function checkEmail(EmailRequest $request) {
        $user = $this->authService->checkEmailAvailability($request->email);

        return response()->json(["is_user_exist" => $user], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function refreshToken(Request $request) {
        $refreshTokenData =  $this->authService->refreshToken($request);

        return response()->json($refreshTokenData, Response::HTTP_OK);
    }
}
