<?php

namespace App\Services;

use App\Http\Resources\UserContactResource;

class UserContactService {
  public function showUserContact($user) {
    $userContact = $user->userContacts;

    if (!$userContact) {
      return response()->json(['error' => 'User Contact not found'], 404);
    }

    return new UserContactResource($userContact);
  }

  public function updateUserContact($user, $validatedRequest) {
    $userContact = $user->userContacts;

    if (!$userContact) {
      return response()->json(['error' => 'User Contact not found'], 404);
    }

    // Update the user contact information
    $userContact->update([
      'phone' => $validatedRequest['phone'] ?? "",
      'city' => $validatedRequest['city'] ?? "",
      'province' => $validatedRequest['province'] ?? "",
      'country' => $validatedRequest['country'] ?? "",
      'zip_code' => $validatedRequest['zip_code'] ?? "",
      'birth_date' => $validatedRequest['birth_date'] ?? "",
    ]);

    // Optionally, you can return the updated user contact resource
    return response()->json(['message' => 'User Contact updated successfully']);
  }
}
