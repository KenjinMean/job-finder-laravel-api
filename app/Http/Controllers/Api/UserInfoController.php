<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Services\UserInfoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\users\UserInfoStoreRequest;
use App\Http\Requests\users\UserInfoUpdateReqeust;
use App\Http\Requests\users\UserInfoCoverImageUpdateRequest;
use App\Http\Requests\users\UserInfoProfileImageUpdateRequest;

class UserInfoController extends Controller {

    private $userInfoService;

    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }

    // |--------------------------------------------------------------------------
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userInfo = $this->userInfoService->index($user);

        return response()->json($userInfo);
    }

    // |--------------------------------------------------------------------------
    public function store(UserInfoStoreRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->store($user, $validatedRequest);

        return response()->json(['message' => 'User Info Created successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function update(UserInfoUpdateReqeust $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->update($user, $validatedRequest);

        return response()->json(['message' => 'User Info Updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateProfileImage(UserInfoProfileImageUpdateRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->updateImage($user, $validatedRequest, "profile");

        return response()->json(['message' => 'Profile photo updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateCoverImage(UserInfoCoverImageUpdateRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userInfoService->updateImage($user, $validatedRequest, "cover");

        return response()->json(['message' => 'Cover photo updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function delete() {
        $user = JwtHelper::getUserFromToken();
        $this->userInfoService->delete($user);

        return response()->json(['message' => 'User Info Deleted successfully'], Response::HTTP_OK);
    }

    public function deleteProfileImage() {
        $user = JwtHelper::getUserFromToken();
        $this->userInfoService->deleteImage($user, "profile");

        return response()->json(['message' => 'Profile photo deleted successfully'], Response::HTTP_OK);
    }

    public function deleteCoverImage() {
        $user = JwtHelper::getUserFromToken();
        $this->userInfoService->deleteImage($user, "cover");

        return response()->json(['message' => 'Cover photo deleted successfully'], Response::HTTP_OK);
    }
}
