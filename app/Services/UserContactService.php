<?php

namespace App\Services;

use App\Models\UserContact;
use Illuminate\Http\Response;
use App\Http\Resources\UserContactResource;

class UserContactService {
  public function createUserContact($user, $data) {
    $data['user_id'] = $user->id;
    $userContact = UserContact::create($data);
    return $userContact;
  }

  public function showUserContact($user) {
    $userContact = $user->userContact;
    if (!$userContact) {
      return (object) [];
    }
    return new UserContactResource($userContact);
  }

  public function updateUserContact($user, $validatedRequest) {
    $userContact = $user->userContact;
    if ($userContact) {
      $userContact->fill($validatedRequest)->save();
      return response()->json(['message' => 'User Contact Updated Successfully'], Response::HTTP_OK);
    } else {
      return response()->json(['error' => 'User Contact Not Found'], Response::HTTP_NOT_FOUND);
    }
  }

  public function deleteUserContact($user) {
    $userContact = $user->userContact;
    if ($userContact) {
      $userContact->delete();
      return response()->json(['message' => 'User Contact Deleted Successfully'], Response::HTTP_OK);
    } else {
      return response()->json(['error' => 'User Contact Not Found'], Response::HTTP_NOT_FOUND);
    }
  }
}
