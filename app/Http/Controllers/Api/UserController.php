<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Skill;
use App\Helpers\JwtHelper;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManageSkillRequest;
use App\Http\Resources\SkillResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller {

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index() {
        return response(User::paginate(10));
    }

    public function store(RegisterUserRequest $request) {
        $validatedRequest = $request->validated();
        $user = $this->userService->createUser($validatedRequest);
        return response()->json(['message' => $user]);
    }

    public function show() {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('view', $user);
        $response = $this->userService->getUser($user);
        return response()->json(["user" => $response]);
    }

    public function update(UpdateUserRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->authorize('update', $user);
        $this->userService->updateUser($user, $validatedRequest);
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy() {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('delete', $user);
        $this->userService->deleteUser($user);
        return response()->json(['message' => 'User deleted successfully']);
    }

    // Manage User Skill Controllers
    /** ------------------------------------------------------------------ */
    public function getUserSkills(User $user) {
        return response()->json(["skills" => SkillResource::collection($user->skills)]);
    }

    public function addUserSkill(User $user, Skill $skill) {
        $user->skills()->syncWithoutDetaching([$skill->id]);
        return response()->json(['message' => 'Skill added to user successfully']);
    }

    // method to add multiple skills
    // accepts skill_ids array that contains skill ids like this { "skill_ids": ["9", "10"] }
    public function addUserSkills(ManageSkillRequest $request,  User $user) {
        $skillIds = $request->input('skill_ids');
        $user->skills()->syncWithoutDetaching($skillIds);
        return response()->json(['message' => 'Skill added to user successfully']);
    }

    public function removeUserSkill(User $user, Skill $skill) {
        $user->skills()->detach($skill);
        return response()->json(["message" => "Skill removed successfully"]);
    }

    // method to remove multiple skills
    // accepts skill_ids array that contains skill ids like this { "skill_ids": ["9", "10"] }
    public function removeUserSkills(ManageSkillRequest $request, User $user) {
        $skillIds = $request->input('skill_ids');
        $user->skills()->detach($skillIds);
        return response()->json(["message" => "Skills removed successfully"]);
    }
}
