<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;

class EmailVerificationController extends Controller {
    private $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService) {
        $this->emailVerificationService = $emailVerificationService;
    }

    # Email verification
    public function verificationNotice(Request $request) {
        try {
            return $this->emailVerificationService->verificationNotice($request);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public  function verificationVerify(Request $request, $id, $hash) {
        try {
            return $this->emailVerificationService->verificationVerify($request, $id, $hash);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function verificationSend(Request $request) {
        try {
            return $this->emailVerificationService->verificationSend($request);
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
