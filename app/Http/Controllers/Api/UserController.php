<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Skill;
use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Requests\ManageSkillRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller {

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    // unused controller for future admin use
    // |--------------------------------------------------------------------------
    public function index() {
        try {
            $this->authorize('index');

            return response()->json(["users" => User::paginate(10)], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch user Unauthorized access", Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch users failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // unused controller for future use only
    // FIX: create a request for storing user
    // |--------------------------------------------------------------------------
    public function store(Request $request) {
        try {
            $this->authorize('store');
            $validatedRequest = $request->validated();
            $user = $this->userService->createUser($validatedRequest);

            return response()->json(['message' => $user], Response::HTTP_CREATED);
        } catch (AuthorizationException $e) {
            return ResponseHelper::generateErrorResponse($e, "Store user Unauthorized access", Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Store users failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function show() {
        try {
            $user = JwtHelper::getUserFromToken();
            $this->authorize('view', $user);
            $response = $this->userService->getUser($user);

            return response()->json(["user" => $response], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch user Unauthorized access", Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch user failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // unused controller for future use only
    // FIX: create a request for updating user
    // |--------------------------------------------------------------------------
    public function update(Request $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->authorize('update', $user);
            $this->userService->updateUser($user, $validatedRequest);

            return response()->json(['message' => 'Updated successfully'], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return ResponseHelper::generateErrorResponse($e, "Update user Unauthorized access", Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Update user failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function destroy() {
        try {
            $user = JwtHelper::getUserFromToken();
            $this->authorize('delete', $user);
            $this->userService->deleteUser($user);

            return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (AuthorizationException $e) {
            return ResponseHelper::generateErrorResponse($e, "Delete user Unauthorized access", Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Delete user failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Manage User Skill Controllers

    /** ------------------------------------------------------------------ */
    public function getUserSkills(User $user) {
        try {
            return response()->json(["skills" => SkillResource::collection($user->skills)], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch user skills failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** ------------------------------------------------------------------ */
    public function addUserSkill(User $user, Skill $skill) {
        try {
            $user->skills()->syncWithoutDetaching([$skill->id]);

            return response()->json(['message' => 'Skill added to user successfully'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Add user skill failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * UPDATE: check if you can refactor the add and remoce user skills to handle if provided with one
     * skill only it will use the the method to add one skill only, and if provided with array of skills
     * use the method to add multiple skills 
     */
    // method to add multiple skills
    // accepts skill_ids array that contains skill ids like this { "skill_ids": ["9", "10"] }
    /** ------------------------------------------------------------------ */
    public function addUserSkills(ManageSkillRequest $request,  User $user) {
        try {
            $skillIds = $request->input('skill_ids');
            $user->skills()->syncWithoutDetaching($skillIds);

            return response()->json(['message' => 'Skill added to user successfully'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Add user skills failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** ------------------------------------------------------------------ */
    public function removeUserSkill(User $user, Skill $skill) {
        try {
            $user->skills()->detach($skill);

            return response()->json(["message" => "Skill removed successfully"], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Remove user skill failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // method to remove multiple skills
    // accepts skill_ids array that contains skill ids like this { "skill_ids": ["9", "10"] }
    /** ------------------------------------------------------------------ */
    public function removeUserSkills(ManageSkillRequest $request, User $user) {
        try {
            $skillIds = $request->input('skill_ids');
            $user->skills()->detach($skillIds);

            return response()->json(["message" => "Skills removed successfully"], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Remove user skills failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
