<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\users\UserShowRequest;
use App\Http\Requests\users\UserStoreRequest;
use App\Http\Requests\users\UserUpdateEmailRequest;
use App\Http\Requests\users\UserUpdatePasswordRequest;

class UserController extends Controller {

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    // |--------------------------------------------------------------------------
    public function index() {
        $this->authorize('index');

        return response()->json(["users" => User::paginate(10)], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function store(UserStoreRequest $request) {
        $this->authorize('store');
        $validatedRequest = $request->validated();
        $user = $this->userService->store($validatedRequest);

        return response()->json(['message' => $user], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    # To IMPLEMENT: have this accept a parameter that you can specify what relationship to load
    public function show(UserShowRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('view', $user);
        $validatedRequest = $request->validated();
        $response = $this->userService->show($user, $validatedRequest);

        return response()->json(["user" => $response], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateUserEmail(UserUpdateEmailRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userService->updateUserEmail($user, $validatedRequest);

        return response()->json(['message' => 'Email updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateUserPassword(UserUpdatePasswordRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userService->updateUserPassword($user, $validatedRequest);

        return response()->json(['message' => 'Password updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function update(Request $request) {  // FIX: create a request for updating user
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->authorize('update', $user);
        $this->userService->updateUser($user, $validatedRequest);

        return response()->json(['message' => 'Updated successfully'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function destroy() {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('delete', $user);
        $this->userService->deleteUser($user);

        return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
