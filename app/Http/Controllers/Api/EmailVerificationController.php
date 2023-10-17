<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use App\Helpers\JwtHelper;
use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;

class EmailVerificationController extends Controller {
    private $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService) {
        $this->emailVerificationService = $emailVerificationService;
    }

    # check if user email is verified
    public function verificationNotice(Request $request) {
        try {
            return $this->emailVerificationService->verificationNotice($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    # verifies user email
    public  function verificationVerify($id, $hash) {
        try {
            return $this->emailVerificationService->verificationVerify($id, $hash);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function verificationSend() {
        try {
            $user = JwtHelper::getUserFromToken();
            return $this->emailVerificationService->verificationSend($user);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function verificationRedirect(Request $request) {
        try {
            return $this->emailVerificationService->verificationRedirect($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
