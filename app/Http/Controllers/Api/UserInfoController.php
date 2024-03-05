<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Services\UserInfoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Requests\UpdateCoverImageRequest;
use App\Http\Requests\UpdateProfileImageRequest;
use Illuminate\Http\Response;

class UserInfoController extends Controller {

    private $userInfoService;

    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }

    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userInfo = $this->userInfoService->index($user);
        return response()->json($userInfo);
    }

    public function update(UpdateUserInfoRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->updateUserInfo($user, $validatedRequest);
        return response()->json(['message' => 'Updated successfully'], Response::HTTP_OK);
    }

    public function updateProfileImage(UpdateProfileImageRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->updateProfileImage($user, $validatedRequest);
        return response()->json(['message' => 'Profile photo updated successfully'], Response::HTTP_OK);
    }

    public function updateCoverImage(UpdateCoverImageRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->updateCoverImage($user, $validatedRequest);
        return response()->json(['message' => 'Cover photo updated successfully'], Response::HTTP_OK);
    }
}
