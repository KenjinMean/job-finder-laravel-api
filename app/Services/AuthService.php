<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService {
    public function register($validatedRequest) {
        $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
        $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

        $existingUser = User::where('email', $validatedRequest['email'])->first();
        if ($existingUser) {
            if (!empty($existingUser->github_id)) {
                return response()->json(['message' => 'This email is already associated with a GitHub account. Would you like to log in with GitHub instead?', "error" => "Account Conflict: Email Already Linked", "provider" => "github"], Response::HTTP_CONFLICT);
            }
            if (!empty($existingUser->google_id)) {
                return response()->json(['message' => 'This email is already associated with a Google account. Would you like to log in with Google instead?', "error" => "Account Conflict: Email Already Linked", "provider" => "google"], Response::HTTP_CONFLICT);
            }
            return ResponseHelper::errorResponse('This email already exists. Please log in using your email and password.', Response::HTTP_CONFLICT, "Account Conflict: Email Already taken");
        }

        $user = User::create([
            'email' => $validatedRequest['email'],
            'password' => $validatedRequest['password'],
        ]);

        UserInfo::create([
            'firstName' => $validatedRequest['name'],
            'user_id' => $user->id,
            'profile_image' => $defaultProfileImagePath,
            'cover_image' => $defaultCoverImagePath,
        ]);

        # Uncomment to generate a email verification
        // event(new Registered($user));

        // JWT token generation
        $token = JWTAuth::fromUser($user);
        $user = User::with('userInfo')->find($user->id);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function login($validatedCredentials) {
        $user = User::where('email', $validatedCredentials['email'])->first();
        if ($user) {
            if (!empty($user->github_id) || !empty($user->google_id)) {
                return ResponseHelper::errorResponse('Invalid credentials, please double-check your email and password.', Response::HTTP_UNPROCESSABLE_ENTITY, "Authentication Failed: Incorrect Email or Password");
            }
        }
        if (!Auth::attempt($validatedCredentials)) {
            return ResponseHelper::errorResponse('Invalid credentials, please double-check your email and password.', Response::HTTP_UNPROCESSABLE_ENTITY, "Authentication Failed: Incorrect Email or Password");
        }
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $user = User::with('userInfo')->find(Auth::id());

        return response(compact('user', 'token'));
    }

    public function logout() {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
    }
}
