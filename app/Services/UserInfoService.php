<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserInfoService {
  public function showUserInfo($user) {
    return $user->userInfo;
  }

  public function updateUserInfo($userInfo, $validatedRequest) {
    $userInfo->update([
      'firstName' => $validatedRequest['firstName'],
      'lastName' => $validatedRequest['lastName'],
      'headline' => $validatedRequest['headline'],
      'additionalName' => $validatedRequest['additionalName'],
      'pronouns' => $validatedRequest['pronouns'],
      'about' => $validatedRequest['about'],
      'location' => $validatedRequest['location'],
    ]);
  }

  # NOTE can refactor this update profile image to update cover image
  public function updateProfileImage($user, $validatedRequest) {
    try {
      $path = null;
      $oldProfileImage = $user->userInfo->profile_image;
      DB::beginTransaction();

      if (isset($validatedRequest['profile_image'])) {
        // New image is provided
        $path = Storage::disk('public')->put('user_profile_images', $validatedRequest['profile_image']);
        $path = 'storage/' . str_replace('\\', '/', $path);
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

  public function updateCoverImage($user, $validatedRequest) {
    try {
      $path = null;
      $oldCoverImage = $user->userInfo->cover_image;
      DB::beginTransaction();

      if (isset($validatedRequest['cover_image'])) {
        // New image is provided
        $path = Storage::disk('public')->put('user_cover_images', $validatedRequest['cover_image']);
        $path = 'storage/' . str_replace('\\', '/', $path);
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
