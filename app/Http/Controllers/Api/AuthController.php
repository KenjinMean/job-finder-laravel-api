<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\Response;
use App\Helpers\ExceptionHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            return response([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
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

    # Email verification
    public function verificationNotice(Request $request) {
        try {
            return $this->authService->verificationNotice($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public  function verificationVerify(Request $request, $id, $hash) {
        try {
            return $this->authService->verificationVerify($request, $id, $hash);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function verificationSend(Request $request) {
        try {
            return $this->authService->verificationSend($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function verificationRedirect(Request $request) {
        try {
            return $this->authService->verificationRedirect($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
