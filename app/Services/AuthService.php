<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use App\Helpers\JwtHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AccountConflictException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService {

    public function generateRefreshToken(User $user) {
        $refreshToken = Str::random(60);
        $user->update(['refresh_token' => hash('sha256', $refreshToken)]);
        return $refreshToken;
    }

    // |--------------------------------------------------------------------------
    public function login($request) {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request['email'])->first();

        if ($user) {
            if (!empty($user->github_id) || !empty($user->google_id)) {
                throw new UnauthorizedHttpException("", 'Your account is linked with another authentication method. Please use that method to log in.', null, Response::HTTP_UNAUTHORIZED);
            }
        }
        if (!Auth::attempt($credentials)) {
            throw new UnauthorizedHttpException("", 'Invalid email or password. Please check your credentials and try again.', null, Response::HTTP_UNAUTHORIZED);
        }

        return JwtHelper::generateAccessToken($user);
    }

    // |--------------------------------------------------------------------------
    public function register($request) {
        $existingUser = User::where('email', $request['email'])->first();

        if ($existingUser) {
            if (!empty($existingUser->github_id)) {
                throw new AccountConflictException('This email is already associated with a GitHub account. Log in with using GitHub instead.', 'github');
            }
            if (!empty($existingUser->google_id)) {
                throw new AccountConflictException('This email is already associated with a Google account. log in with using Google instead.', "google");
            }
            throw new AccountConflictException('This email already exists. Please log in using your email and password.', "");
        }

        $user = User::create([
            'email' => $request['email'],
            'password' => $request['password'],
        ]);

        UserInfo::create([
            'user_id' => $user->id,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
        ]);

        return JwtHelper::generateAccessToken($user);
    }

    // |--------------------------------------------------------------------------
    public function logout($user) {
        $user->refresh_token = null;
        $user->save();
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
    }

    // |--------------------------------------------------------------------------
    public function checkEmailAvailability($email) {
        $user = User::where('email', $email)->exists();

        return $user;
    }

    // |--------------------------------------------------------------------------
    public function refreshToken() {
        return JwtHelper::refreshAccessToken();
    }

    // public function refreshToken() {
    //     return JwtHelper::refreshAccessToken();
    // }

    #CAN IMPLEMENT THIS using HTTPS
    // public function refreshToken($request) {
    //     $refreshToken = $request->input('refresh_token');
    //     if (!$refreshToken) {
    //         return response()->json(['error' => 'Invalid refresh token'], 401);
    //     }
    //     $user = User::where('refresh_token', hash('sha256', $refreshToken))->first();
    //     if (!$user) {
    //         return response()->json(['error' => 'Invalid refresh token'], 401);
    //     }
    //     $accessToken = JWTAuth::parseToken()->refresh();
    //     return response()->json([
    //         'refresh_token' => $refreshToken,
    //         'access_token' => $accessToken,
    //         'token_type' => 'bearer',
    //         'expires_in' => JWTAuth::factory()->getTTL() * 60, // in minutes 
    //     ]);
    // }
}
