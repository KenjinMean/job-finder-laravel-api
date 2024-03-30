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
        try {
            $AuthenticatedUserData = $this->authService->login($request);

            return response()->json($AuthenticatedUserData, Response::HTTP_OK);
        } catch (UnauthorizedHttpException $e) {
            return ResponseHelper::generateErrorResponse($e, "Unauthorized Exception", $e->getCode());
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Login failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function register(RegisterRequest $request) {
        try {
            $AuthenticatedUserData = $this->authService->register($request);

            return response()->json($AuthenticatedUserData, Response::HTTP_CREATED);
        } catch (AccountConflictException $e) {
            return ResponseHelper::generateErrorResponse($e, "Account Conflict Exception", $e->getCode(), ["provider" => $e->getProvider()]);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Register failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function logout() {
        try {
            $user = JwtHelper::getUserFromToken();
            $this->authService->logout($user);

            return response(['message' => 'User logged out successfully.'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Logout failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function checkEmail(EmailRequest $request) {
        try {
            $user = $this->authService->checkEmailAvailability($request->email);

            return response()->json(["is_user_exist" => $user], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Checking email failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function refreshToken(Request $request) {
        try {
            $refreshTokenData =  $this->authService->refreshToken($request);

            return response()->json($refreshTokenData, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Refresh token failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
