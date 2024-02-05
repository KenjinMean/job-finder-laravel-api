<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Services\UserExperienceService;
use Illuminate\Http\Request;

class UserExperienceController extends Controller {

    private $userExperienceService;

    public function __construct(UserExperienceService $userExperienceService) {
        $this->userExperienceService = $userExperienceService;
    }

    public function show() {
        try {
            $user = JwtHelper::getUserFromToken();
            $userInfo = $this->userExperienceService->showUserExperience($user);
            return response()->json($userInfo);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
