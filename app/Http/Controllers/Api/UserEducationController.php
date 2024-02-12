<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserEducationRequest;
use App\Services\UserEducationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserEducationController extends Controller {
    /* ----------------------------------------------------------- */
    private $userEducationService;
    public function __construct(UserEducationService $userEducationService) {
        $this->userEducationService = $userEducationService;
    }

    /* ----------------------------------------------------------- */
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userEducation = $this->userEducationService->index($user);
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
    public function update(StoreUserEducationRequest $request, $id) {
        $user = JwtHelper::getUserFromToken();
        $validatedData = $request->validated();

        $response = $this->userEducationService->updateUserEducation($validatedData, $id,  $user);
        return response()->json($response, Response::HTTP_OK);

        // return response()->json(["id" => $request->all()]);
    }

    /* ----------------------------------------------------------- */
    public function destroy($user_education_id) {
        $this->userEducationService->destroyUserEducation($user_education_id);
        return response()->json(['message' => 'User education record deleted successfully'], 200);
    }
}
