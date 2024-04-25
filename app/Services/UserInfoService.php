<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserInfoResource;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserInfoService {
  public function index(User $user): UserInfoResource {
    $userInfo = $user->userInfo;

    if (!$userInfo) {
      throw new NotFoundHttpException('User info not found');
    }

    return new UserInfoResource($userInfo);
  }

  // |--------------------------------------------------------------------------
  public function store(User $user, array $validatedRequest): void {
    if ($user->userInfo) {
      throw new ConflictHttpException('User already has a contact');
    }

    $validatedRequest['user_id'] = $user->id;
    UserInfo::create($validatedRequest);
  }


  // |--------------------------------------------------------------------------
  public function update(User $user, array $validatedRequest) {
    $userInfo = $user->userInfo;
    $userInfo->fill($validatedRequest)->save();
  }

  // |--------------------------------------------------------------------------
  public function delete(User $user) {
    if ($user->userInfo) {
      $user->userInfo->delete();
    }
  }

  // |--------------------------------------------------------------------------
  /**
   * Update profile or cover image for the user.
   *
   * @param \App\Models\User $user The user for whom the image is being updated.
   * @param array $validatedRequest The validated request containing the new image data.
   * @param string $imageType The type of image being updated (profile or cover).
   * @return void
   */
  public function updateImage(User $user, $validatedRequest, string $imageType): void {
    // Upload the new image
    $newImage = Storage::disk('public')->put('user_' . $imageType . '_images', $validatedRequest[$imageType . '_image']);

    // Delete the existing image if it exists
    $existingImage = $user->userInfo->getAttribute($imageType . '_image');
    if ($existingImage && Storage::disk('public')->exists(str_replace('storage/', '', $existingImage))) {
      Storage::disk('public')->delete(str_replace('storage/', '', $existingImage));
    }

    // Convert backslashes to forward slashes in file path
    $newImage = 'storage/' . str_replace('\\', '/', $newImage);

    // Update the user info with the new image path
    $userInfo = $user->userInfo;
    $userInfo->{$imageType . '_image'} = $newImage;
    $userInfo->save();
  }

  // |--------------------------------------------------------------------------
  /**
   * Delete profile or cover image.
   *
   * @param \App\Models\UserInfo $userInfo
   * @param string $imageType
   * @return void
   */
  public function deleteImage(User $user, string $imageType): void {
    $userInfo = $user->userInfo;

    // Check if the user information exists and has the specified image
    if ($userInfo && $userInfo->{$imageType . '_image'}) {
      // Get the path of the existing image
      $existingImage = $userInfo->{$imageType . '_image'};

      // Check if the image exists
      if (Storage::disk('public')->exists(str_replace('storage/', '', $existingImage))) {
        // Delete the image
        Storage::disk('public')->delete(str_replace('storage/', '', $existingImage));
      }

      // Set the image path to null
      $userInfo->{$imageType . '_image'} = null;
      $userInfo->save();
    }
  }
}
