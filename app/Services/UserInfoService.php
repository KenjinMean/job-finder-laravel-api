<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserInfoResource;

class UserInfoService {
  public function index($user) {
    $userInfo = $user->userInfo;

    if (!$userInfo) {
      return response()->json(['error' => 'User info not found'], 404);
    }

    return new UserInfoResource($userInfo);
  }

  public function store() {
  }

  public function updateUserInfo($user, $validatedRequest) {
    $userInfo = $user->userInfo;
    $userInfo->fill($validatedRequest)->save();
  }

  # NOTE can refactor this update profile image to update cover image
  private function deleteOldProfileImage($oldProfileImage) {
    // Extract the path from the old image URL
    $oldProfileImage = str_replace('storage/', '', $oldProfileImage);

    // Delete the old image if not default image
    if ($oldProfileImage !== 'user_profile_images/default-avatar.png') {
      Storage::disk('public')->delete($oldProfileImage);
    }
  }

  public function updateProfileImage($user, $validatedRequest) {
    try {
      $path = null;
      $oldProfileImage = $user->userInfo->profile_image;
      DB::beginTransaction();

      if (isset($validatedRequest['profile_image'])) {
        // New image is provided
        $path = Storage::disk('public')->put('user_profile_images', $validatedRequest['profile_image']);
        $path = 'storage/' . str_replace('\\', '/', $path);
        $this->deleteOldProfileImage($oldProfileImage);
        $user->userInfo->update([
          'profile_image' => $path,
        ]);
      } elseif ($oldProfileImage !== 'storage/user_profile_images/default-avatar.png') {
        // No image is provided, and the old image is not the default image
        $oldProfileImage = str_replace('storage/', '', $oldProfileImage);
        Storage::disk('public')->delete($oldProfileImage);
        $user->userInfo->update([
          'profile_image' => 'storage/user_profile_images/default-avatar.png',
        ]);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($path) {
        $path = str_replace('storage/', '', $path);
        Storage::disk('public')->delete($path);
      }
      throw $e;
    }
  }

  private function deleteOldCoverImage($oldCoverImage) {
    // Extract the path from the old image URL
    $oldCoverImage = str_replace('storage/', '', $oldCoverImage);

    // Delete the old image if not default image
    if ($oldCoverImage !== 'user_cover_images/default-cover.jpg') {
      Storage::disk('public')->delete($oldCoverImage);
    }
  }

  public function updateCoverImage($user, $validatedRequest) {
    try {
      $path = null;
      $oldCoverImage = $user->userInfo->cover_image;
      DB::beginTransaction();

      if (isset($validatedRequest['cover_image'])) {
        // New image is provided
        $path = Storage::disk('public')->put('user_cover_images', $validatedRequest['cover_image']);
        $path = 'storage/' . str_replace('\\', '/', $path);
        $this->deleteOldCoverImage($oldCoverImage);
        $user->userInfo->update([
          'cover_image' => $path,
        ]);
      } elseif ($oldCoverImage !== 'storage/user_cover_images/default-cover.jpg') {
        // No image is provided, and the old image is not the default image
        $oldCoverImage = str_replace('storage/', '', $oldCoverImage);
        Storage::disk('public')->delete($oldCoverImage);
        $user->userInfo->update([
          'cover_image' => 'storage/user_cover_images/default-cover.jpg',
        ]);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($path) {
        $path = str_replace('storage/', '', $path);
        Storage::disk('public')->delete($path);
      }
      throw $e;
    }
  }
}
