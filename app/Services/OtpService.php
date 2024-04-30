<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OtpService {

  private function generateOtpCode($length) {
    $characters = '0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
      $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
  }

  /** --------------------------------------------------------- */
  public function requestOtp($request) {
    $email = $request->input('email');

    $user = User::where('email', $email)->firstOrFail();

    // Generate OTP code
    $otpCode = $this->generateOtpCode(4);

    // Save hashed OTP code and expiration time
    $user->otp_code =  Hash::make($otpCode);
    $user->otp_expires_at = Carbon::now()->addMinutes(5);
    $user->save();

    $userName = $user->userInfo ? $user->userInfo->first_name : $user->email;

    // Send email with OTP code
    try {
      Mail::to($user)->send(new OtpMail($userName, $otpCode));
    } catch (\Exception $e) {
      throw new Exception('Failed to send OTP code. Please try again later.');
    }

    $resendTimerSeconds = config("otp.otp_resend_timer_seconds") + 60;

    return $resendTimerSeconds;
  }

  /** --------------------------------------------------------- */
  public function verifyOtp($request) {
    $email = $request->input('email');
    $otp = $request->input('otp');

    $user = User::where('email', $email)->firstOrFail();

    // Check if user requested for OTP
    if (!$user->otp_code) {
      throw new NotFoundHttpException('No OTP request found for this user.');
    }

    // Check if provided OTP matches the user's OTP
    if (!Hash::check($otp, $user->otp_code)) {
      throw new UnauthorizedHttpException('', 'The OTP entered is incorrect. Please try again.');
    }

    // Check if OTP is expired
    if (Carbon::now()->gt($user->otp_expires_at)) {
      throw new UnauthorizedHttpException('', 'The OTP has expired. Please request a new OTP.');
    }

    // OTP is valid and not expired
    $user->otp_code = null;
    $user->otp_expires_at = null;
    $user->email_verified_at = now();
    $user->save();

    $userResource = new UserResource($user);

    return $userResource;
  }
}
