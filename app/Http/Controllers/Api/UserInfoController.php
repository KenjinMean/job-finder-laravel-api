<?php

namespace App\Http\Controllers\Api;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use App\Helpers\JwtHelper;
use App\Services\UserInfoService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateCoverImageRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Requests\UpdateProfileImageRequest;

class UserInfoController extends Controller {

    private $userInfoService;

    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }

    public function show() {
        try {
            $user = JwtHelper::getUserFromToken();
            $userInfo = $this->userInfoService->showUserInfo($user);
            return response()->json($userInfo);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function update(UpdateUserInfoRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userInfoService->updateUserInfo($user, $validatedRequest);
            return response()->json(['message' => 'Updated successfully']);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateProfileImage(UpdateProfileImageRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userInfoService->updateProfileImage($user, $validatedRequest);
            return response()->json(['message' => 'Profile photo updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(["message" => $e->getMessage()]);
        }
    }

    public function updateCoverImage(UpdateCoverImageRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userInfoService->updateCoverImage($user, $validatedRequest);
            return response()->json(['message' => 'Cover photo updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(["message" => $e->getMessage()]);
        }
    }
}
