<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Services\UserEducationService;
use Illuminate\Http\Request;

class UserEducationController extends Controller {
    private $userEducationService;

    public function __construct(UserEducationService $userEducationService) {
        $this->userEducationService = $userEducationService;
    }

    public function show() {
        try {
            $user = JwtHelper::getUserFromToken();
            $userEducation = $this->userEducationService->getUserEducation($user);
            return response()->json($userEducation);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function getUserEducation() {
    }
}
