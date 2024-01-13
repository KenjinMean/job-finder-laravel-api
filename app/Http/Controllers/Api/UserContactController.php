<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ExceptionHelper;
use App\Helpers\JwtHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserContactRequest;
use App\Services\UserContactService;
use App\Services\UserInfoService;
use Illuminate\Http\Request;

class UserContactController extends Controller {

    private  $userContactService;

    public function __construct(UserContactService $userContactService) {
        $this->userContactService = $userContactService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
    }

    /**
     * Display the specified resource.
     */
    public function show() {
        try {
            $user = JwtHelper::getUserFromToken();
            $userContact = $this->userContactService->showUserContact($user);
            return response()->json($userContact);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
