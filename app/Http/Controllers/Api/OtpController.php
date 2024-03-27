<?php

namespace App\Http\Controllers\Api;

use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Http\Requests\OTPRequest;
use App\Http\Controllers\Controller;

class OtpController extends Controller {

    private $OTPService;

    public function __construct(OtpService $OTPService) {
        $this->OTPService = $OTPService;
    }

    /** --------------------------------------------------------- */
    public function requestOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        return $this->OTPService->requestOtp($email);
    }

    /** --------------------------------------------------------- */
    public function verifyOtp(OTPRequest $request) {
        $email = $request->input('email');
        $otp = $request->input('otp');
        return $this->OTPService->verifyOtp($email, $otp);
    }
}
