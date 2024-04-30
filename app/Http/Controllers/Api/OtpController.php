<?php

namespace App\Http\Controllers\Api;

use App\Services\OtpService;
use App\Http\Requests\OTPRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Controllers\Controller;

class OtpController extends Controller {

    private $OTPService;

    public function __construct(OtpService $OTPService) {
        $this->OTPService = $OTPService;
    }

    /** --------------------------------------------------------- */
    public function requestOtp(EmailRequest $request) {
        $resendTimerSeconds = $this->OTPService->requestOtp($request);

        return response()->json([
            'message' => 'OTP code sent successfully!',
            'resend_timer_seconds' => $resendTimerSeconds
        ]);
    }

    /** --------------------------------------------------------- */
    public function verifyOtp(OTPRequest $request) {
        $userResource = $this->OTPService->verifyOtp($request);

        return response()->json([
            "message" => 'OTP verified successfully!',
            "user" => $userResource
        ]);
    }
}
