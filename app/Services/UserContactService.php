<?php

namespace App\Services;

use App\Models\UserContact;
use App\Http\Resources\UserContactResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserContactService {
  public function createUserContact($user, $data) {
    if ($user->userContact) {
      throw new ConflictHttpException('User already has a contact');
    }
    $data['user_id'] = $user->id;
    $userContact = UserContact::create($data);

    return new UserContactResource($userContact);
  }

  /** ------------------------------------------------------------------ */
  public function showUserContact($user) {
    $userContact = $user->userContact;
    if ($userContact === null) {
      return null;
    }

    return new UserContactResource($userContact);
  }

  /** ------------------------------------------------------------------ */
  public function updateUserContact($user, $validatedRequest) {
    $userContact = $user->userContact;
    if ($userContact === null) {
      throw new ModelNotFoundException("Model not found, User contact does not exist");
    }

    $userContact->fill($validatedRequest)->save();
  }

  /** ------------------------------------------------------------------ */
  public function deleteUserContact($user) {
    $userContact = $user->userContact;

    if ($userContact === null) {
      throw new ModelNotFoundException("Model not found, User contact does not exist");
    }
    $userContact->delete();

    // if ($userContact) {
    //   $userContact->delete();
    //   return response()->json(['message' => 'User Contact Deleted Successfully'], Response::HTTP_OK);
    // } else {
    //   return response()->json(['error' => 'User Contact Not Found'], Response::HTTP_NOT_FOUND);
    // }
  }
}
