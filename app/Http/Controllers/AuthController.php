<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller {

    public function register(RegisterUserRequest $request) {
        $validatedData = $request->validated();
        $user = User::create($validatedData);

        // Automatically log in the user
        Auth::login($user);
        $token = $user->createToken('main')->plainTextToken;

        return response(compact('user', 'token'));
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'provided email or password is incorrect'
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response(compact('user', 'token'));
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'User logged out successfully.'], Response::HTTP_NO_CONTENT);
    }
}
