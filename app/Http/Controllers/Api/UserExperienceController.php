<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Models\UserWorkExperience;
use App\Http\Controllers\Controller;
use App\Services\UserExperienceService;
use App\Http\Requests\StoreUserExperienceRequest;

class UserExperienceController extends Controller {
    /* ----------------------------------------------------------- */
    private $userExperienceService;
    public function __construct(UserExperienceService $userExperienceService) {
        $this->userExperienceService = $userExperienceService;
    }

    /* ----------------------------------------------------------- */
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('view', [UserWorkExperience::class, $user]);

        $userInfo = $this->userExperienceService->index($user);
        return response()->json($userInfo);
    }

    /* ----------------------------------------------------------- */
    public function show($experienceId) {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('view', [UserWorkExperience::class, $user]);

        $response = $this->userExperienceService->show($experienceId);
        return response()->json($response);
    }

    /* ----------------------------------------------------------- */
    public function store(StoreUserExperienceRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $this->authorize('create', [UserWorkExperience::class, $user]);

        $validatedData = $request->validated();
        $this->userExperienceService->store($validatedData, $user);
        return response()->json(["message" => "User Experience Created Successfully"], Response::HTTP_CREATED);
    }

    /* ----------------------------------------------------------- */
    public function update(StoreUserExperienceRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $userWorkExperience = UserWorkExperience::findOrFail($request->id);
        $this->authorize('update', $userWorkExperience);

        $validatedData = $request->validated();
        $this->userExperienceService->update($validatedData, $user);
        return response()->json(['message' => 'User experience updated successfully'], Response::HTTP_OK);
    }

    /* ----------------------------------------------------------- */
    public function destroy($experienceId) {
        $this->authorize('delete', [UserWorkExperience::class, $experienceId]);

        $this->userExperienceService->destroy($experienceId);
        return response()->json(['message' => 'User experience record deleted successfully'], Response::HTTP_OK);
    }
}
