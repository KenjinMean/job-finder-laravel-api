<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Http\Requests\UpdateUserContactRequest;
use App\Models\User;

class UserContactController extends Controller {

    private  $userContactService;

    public function __construct(UserContactService $userContactService) {
        $this->userContactService = $userContactService;
    }

    public function store(UpdateUserContactRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userContactService->createUserContact($user, $validatedRequest);
        return response()->json(['message' => 'Created successfully']);
    }

    public function show() {
        $user = JwtHelper::getUserFromToken();
        $userContact = $this->userContactService->showUserContact($user);
        return response()->json($userContact);
    }

    public function update(UpdateUserContactRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userContactService->updateUserContact($user, $validatedRequest);
            return response()->json(['message' => 'Updated successfully']);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function destroy(string $id) {
    }
}
