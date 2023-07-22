<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\URL;

class EmailVerificationService {
  # Email verification
  public function verificationNotice($request) {
    $user = $request->user();
    // Check if the user is authenticated and the email is verified
    if ($user && $user->hasVerifiedEmail()) {
      return response()->json(['message' => 'Email already verified.'], 200);
    }
    return response()->json(['message' => 'Email verification required.'], 403);
  }

  public function verificationVerify($request, $id, $hash) {
    $user = User::findOrFail($id);
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
      return response()->json(['message' => 'Invalid verification link.'], 403);
    }
    if (!$user->hasVerifiedEmail()) {
      $user->markEmailAsVerified();
    }
    return response()->json(['message' => 'Email verified successfully.']);
  }

  # this function handle redirecting in react
  // public function verificationVerify($request, $id, $hash) {
  //   $user = User::findOrFail($id);

  //   if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
  //     return response()->json(['message' => 'Invalid verification link.'], 403);
  //   }

  //   if (!$user->hasVerifiedEmail()) {
  //     $user->markEmailAsVerified();
  //   }

  //   // Generate a signed URL with a verification status parameter
  //   $verificationStatus = $user->hasVerifiedEmail() ? 'verified' : 'not-verified';
  //   $redirectUrl = URL::signedRoute('verification.redirect', ['status' => $verificationStatus]);

  //   // Use FRONT_END_URL from .env to construct the full URL for the React app
  //   $frontEndUrl = env('FRONT_END_URL');
  //   $fullRedirectUrl = $frontEndUrl . '/email-verification-redirect?redirect=' . urlencode($redirectUrl);

  //   // Redirect the user to the full redirect URL
  //   return redirect($fullRedirectUrl);
  // }

  public function verificationSend($request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
  }

  public function verificationRedirect($request) {
    // Extract the signed URL from the 'redirect' query parameter
    $signedUrl = $request->query('redirect');

    // Verify the signature of the signed URL
    if (URL::hasValidSignature($signedUrl)) {
      // Signature is valid, extract the verification status from the signed URL
      $queryParameters = parse_url($signedUrl, PHP_URL_QUERY);
      parse_str($queryParameters, $params);

      // Get the verification status (e.g., 'verified' or 'not-verified')
      $verificationStatus = $params['status'];

      if ($verificationStatus === 'verified') {
        return response()->json(['message' => 'Email verified successfully'], 200);
      } else {
        return response()->json(['message' => 'Email verification failed'], 400);
      }
    } else {
      // Invalid or tampered signed URL
      return response()->json(['message' => 'Invalid verification URL'], 400);
    }
  }
}
