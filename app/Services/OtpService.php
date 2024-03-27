<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
  public function requestOtp($email) {
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
      return response()->json([
        'message' => 'Failed to send OTP code. Please try again later.',
      ], 500);
    }

    return response()->json([
      'message' => 'OTP code sent successfully!',
      'resend_timer_seconds' => config("otp.otp_resend_timer_seconds") + 60,
    ]);
  }

  /** --------------------------------------------------------- */
  public function verifyOtp($email, $otp) {
    $user = User::where('email', $email)->firstOrFail();

    if (Hash::check($otp, $user->otp_code)) {
      if (Carbon::now()->gt($user->otp_expires_at)) {
        return response()->json([
          'error' => 'The OTP has expired. Please request a new OTP.',
        ], 400);
      } else {
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
          'message' => 'OTP verified successfully!',
          'user' => new UserResource($user)
        ]);
      }
    } else {
      return response()->json([
        'error' => 'The OTP entered is incorrect. Please try again.',
      ], 400);
    }
  }
}
