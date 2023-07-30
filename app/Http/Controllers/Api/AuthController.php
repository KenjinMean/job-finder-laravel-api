<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\Response;
use App\Helpers\ExceptionHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller {

    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request) {
        try {
            $validatedRequest = $request->validated();
            return $this->authService->register($validatedRequest);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('Registration failed', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function login(LoginRequest $request) {
        try {
            $validatedCredentials = $request->validated();
            return $this->authService->login($validatedCredentials);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function logout(Request $request) {
        try {
            $this->authService->logout($request);
            return response(['message' => 'User logged out successfully.'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function refreshToken() {
        try {
            $token = $this->authService->refreshToken();
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('Could not refresh token', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
