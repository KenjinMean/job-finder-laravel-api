<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Http\Requests\UpdateUserContactRequest;
use App\Models\User;
use App\Models\UserContact;

class UserContactController extends Controller {

    private  $userContactService;

    public function __construct(UserContactService $userContactService) {
        $this->userContactService = $userContactService;
    }

    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userContact = $this->userContactService->showUserContact($user);
        return response()->json($userContact);
    }

    public function store(UpdateUserContactRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userContactService->createUserContact($user, $validatedRequest);
        return response()->json(['message' => 'User Contact Created Successfully']);
    }

    public function update(UpdateUserContactRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $response = $this->userContactService->updateUserContact($user, $validatedRequest);
        return $response;
    }

    public function destroy() {
        $user = JwtHelper::getUserFromToken();
        $response = $this->userContactService->deleteUserContact($user);
        return $response;
    }
}
