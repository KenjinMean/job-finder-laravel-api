<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\UserEducationService;
use App\Http\Requests\StoreUserEducationRequest;

class UserEducationController extends Controller {
    /* ----------------------------------------------------------- */
    private $userEducationService;
    public function __construct(UserEducationService $userEducationService) {
        $this->userEducationService = $userEducationService;
    }

    /* ----------------------------------------------------------- */
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userEducation = $this->userEducationService->getUserEducations($user);
        return response()->json($userEducation);
    }

    /* ----------------------------------------------------------- */
    public function show($educationId) {
        $user = JwtHelper::getUserFromToken();
        $userEducation = $this->userEducationService->getUserEducation($user, $educationId);
        return response()->json($userEducation);
    }

    /* ----------------------------------------------------------- */
    public function store(StoreUserEducationRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedData = $request->validated();

        $this->userEducationService->createUserEducation($validatedData, $user);
        return response()->json(null, Response::HTTP_CREATED);
    }

    /* ----------------------------------------------------------- */
    public function update(StoreUserEducationRequest $request, $educationId) {
        $user = JwtHelper::getUserFromToken();
        $validatedData = $request->validated();

        $this->userEducationService->updateUserEducation($validatedData, $educationId,  $user);
        return response()->json(["message" => 'User education record updated successfully'], Response::HTTP_OK);
    }

    /* ----------------------------------------------------------- */
    public function destroy($educationId) {
        $this->userEducationService->destroyUserEducation($educationId);
        return response()->json(['message' => 'User education record deleted successfully'], Response::HTTP_OK);
    }
}
