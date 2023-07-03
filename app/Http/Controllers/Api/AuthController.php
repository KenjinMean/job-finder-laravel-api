<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller {

    public function register(RegisterUserRequest $request) {
        $validatedData = $request->validated();
        $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
        $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

        try {
            $user = User::create($validatedData);
            UserInfo::create([
                'firstName' => $validatedData['name'],
                'user_id' => $user->id,
                'profile_image' => $defaultProfileImagePath,
                'cover_image' => $defaultCoverImagePath,
            ]);

            Auth::login($user);

            $user = User::with('userInfo')->find(Auth::id());
            $token = $user->createToken('main')->plainTextToken;

            return response(compact('user', 'token'));
        } catch (\Exception $e) {
            return response([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'provided email or password is incorrect'
            ], 422);
        }

        /** @var User $user */
        // $user = Auth::user();
        $user = User::with('userInfo')->find(Auth::id());
        $token = $user->createToken('main')->plainTextToken;

        return response(compact('user', 'token'));
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'User logged out successfully.'], Response::HTTP_NO_CONTENT);
    }
}
