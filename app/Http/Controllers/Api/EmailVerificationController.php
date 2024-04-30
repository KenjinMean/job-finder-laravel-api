<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;

class EmailVerificationController extends Controller {
    private $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService) {
        $this->emailVerificationService = $emailVerificationService;
    }

    # check if user email is verified
    public function verificationNotice(Request $request) {
        return $this->emailVerificationService->verificationNotice($request);
    }

    # verifies user email
    public  function verificationVerify($id, $hash) {
        return $this->emailVerificationService->verificationVerify($id, $hash);
    }

    public function verificationSend() {
        $user = JwtHelper::getUserFromToken();
        return $this->emailVerificationService->verificationSend($user);
    }

    public function verificationRedirect(Request $request) {
        return $this->emailVerificationService->verificationRedirect($request);
    }
}
