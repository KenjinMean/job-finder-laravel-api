<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserSkillRequest;

class UserController extends Controller {

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index() {
        // return response(User::all());
    }

    public function store(RegisterUserRequest $request) {
        try {
            $validatedRequest = $request->validated();
            $user = $this->userService->createUser($validatedRequest);
            return response()->json(['message' => $user]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function show() {
        try {
            $user = Auth::user();
            $this->authorize('view', $user);
            $response = $this->userService->getUser();
            return response()->json(["user" => $response]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function update(UpdateUserRequest $request) {
        try {
            $user = Auth::user();
            $validatedRequest = $request->validated();
            $this->authorize('update', $user);
            $this->userService->updateUser($user, $validatedRequest);
            return response()->json(['message' => 'Updated successfully']);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function destroy(string $id) {
        try {
            $user = User::findOrFail($id);
            $this->authorize('delete', $user);
            $this->userService->deleteUser($user);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateSkill(UpdateUserSkillRequest $request) {
        try {
            $user = Auth::user();
            $skills = $request->validated()['skills'];
            $this->authorize('update', $user);
            $this->userService->updateSkill($user, $skills);
            return response()->json([
                "message" => "Skills updated successfully."
            ]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
