<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use App\Helpers\JwtHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService {

    public function generateRefreshToken(User $user) {
        $refreshToken = Str::random(60);
        $user->update(['refresh_token' => hash('sha256', $refreshToken)]);
        return $refreshToken;
    }

    // public function register($validatedRequest) {
    //     $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
    //     $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

    //     $existingUser = User::where('email', $validatedRequest['email'])->first();
    //     if ($existingUser) {
    //         if (!empty($existingUser->github_id)) {
    //             return response()->json(['message' => 'This email is already associated with a GitHub account. Would you like to log in with GitHub instead?', "error" => "Account Conflict: Email Already Linked", "provider" => "github"], Response::HTTP_CONFLICT);
    //         }
    //         if (!empty($existingUser->google_id)) {
    //             return response()->json(['message' => 'This email is already associated with a Google account. Would you like to log in with Google instead?', "error" => "Account Conflict: Email Already Linked", "provider" => "google"], Response::HTTP_CONFLICT);
    //         }
    //         return ResponseHelper::errorResponse('This email already exists. Please log in using your email and password.', Response::HTTP_CONFLICT, "Account Conflict: Email Already taken");
    //     }

    //     $user = User::create([
    //         'email' => $validatedRequest['email'],
    //         'password' => $validatedRequest['password'],
    //     ]);

    //     // Uncomment to enable sending Verification Email on registration
    //     // $user->sendEmailVerificationNotification();

    //     UserInfo::create([
    //         'firstName' => $validatedRequest['name'],
    //         'user_id' => $user->id,
    //         'profile_image' => $defaultProfileImagePath,
    //         'cover_image' => $defaultCoverImagePath,
    //     ]);

    //     return JwtHelper::generateAccessToken($user);
    // }

    public function registerUser($validatedRequest) {

        $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
        $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

        $existingUser = User::where('email', $validatedRequest['email'])->first();

        if ($existingUser) {
            if (!empty($existingUser->github_id)) {
                return response()->json(['message' => 'This email is already associated with a GitHub account. log in with using GitHub instead.', "error" => "Account Conflict: Email Already Linked", "provider" => "github"], Response::HTTP_CONFLICT);
            }
            if (!empty($existingUser->google_id)) {
                return response()->json(['message' => 'This email is already associated with a Google account. log in with using Google instead.', "error" => "Account Conflict: Email Already Linked", "provider" => "google"], Response::HTTP_CONFLICT);
            }
            return ResponseHelper::errorResponse('This email already exists. Please log in using your email and password.', Response::HTTP_CONFLICT, "Account Conflict: Email Already taken");
        }

        $user = User::create([
            'email' => $validatedRequest['email'],
            'password' => $validatedRequest['password'],
        ]);

        UserInfo::create([
            'user_id' => $user->id,
            'profile_image' => $defaultProfileImagePath,
            'cover_image' => $defaultCoverImagePath,
        ]);

        return JwtHelper::generateAccessToken($user);
    }


    public function login($validatedCredentials) {
        $user = User::where('email', $validatedCredentials['email'])->first();
        if ($user) {
            if (!empty($user->github_id) || !empty($user->google_id)) {
                return ResponseHelper::errorResponse('Incorrect Email or Password', Response::HTTP_UNPROCESSABLE_ENTITY, "Authentication Failed: Incorrect Email or Password");
            }
        }
        if (!Auth::attempt($validatedCredentials)) {
            return ResponseHelper::errorResponse('Incorrect Email or Password', Response::HTTP_UNPROCESSABLE_ENTITY, "Authentication Failed: Incorrect Email or Password");
        }
        return JwtHelper::generateAccessToken($user);
    }

    public function logout($user) {
        $user->refresh_token = null;
        $user->save();
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
    }

    public function checkEmailAvailability($email) {
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['message' => 'Email already exists'], 409);
        } else {
            return response()->json(['message' => 'Email is available'], 200);
        }
    }

    public function refreshToken() {
        return JwtHelper::refreshAccessToken();
    }

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
