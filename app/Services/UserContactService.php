<?php

namespace App\Services;

use App\Http\Resources\UserContactResource;
use App\Models\UserContact;

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
    $userContact->fill($validatedRequest)->save();
  }
}
