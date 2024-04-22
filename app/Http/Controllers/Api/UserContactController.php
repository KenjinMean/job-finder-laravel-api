<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Http\Requests\UpdateUserContactRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserContactController extends Controller {

    private  $userContactService;

    public function __construct(UserContactService $userContactService) {
        $this->userContactService = $userContactService;
    }

    // can create a class that catch all exception that recieves parameter for error to know what error occured
    /** ------------------------------------------------------------------ */
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userContact = $this->userContactService->showUserContact($user);

        return response()->json($userContact, Response::HTTP_OK);
    }

    /** ------------------------------------------------------------------ */
    public function store(UpdateUserContactRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userContactService->createUserContact($user, $validatedRequest);

        return response()->json(['message' => 'User Contact created successfully'], Response::HTTP_CREATED);
    }

    /** ------------------------------------------------------------------ */
    public function update(UpdateUserContactRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userContactService->updateUserContact($user, $validatedRequest);

        return response()->json(['message' => 'User Contact updated successfully'], Response::HTTP_OK);
    }

    /** ------------------------------------------------------------------ */
    public function destroy() {
        $user = JwtHelper::getUserFromToken();
        $this->userContactService->deleteUserContact($user);

        return response()->json(['message' => 'User Contact deleted successfully'], Response::HTTP_OK);
    }
}
