<?php

/*
|--------------------------------------------------------------------------
| auth API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for auth-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\CheckTokenExpiration;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\EmailVerificationController;

// NOTE: do not rearange routes to prevent route conflict

Route::prefix('auth')->group(function () {
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
    Route::get('/check-email-availability', [AuthController::class, 'checkEmail'])->name('checkEmail');
  });
  Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  });

  # Create user forget password route

  # OAuth service providers route //SOCIALITE
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
    Route::get('/{provider}/get-authorization-url', [SocialAuthController::class, 'getProviderAuthorizationUrl'])->name('social.getAuthUrl');
  });

  # OTP route, email verify OTP
  # rename route to request-email-verify-otp, to clarify the route purpose, same with verify
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::post('/request-otp', [OtpController::class, 'requestOtp'])->middleware(['throttle:6,1']);
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->middleware(['throttle:6,1']);
  });

  # TO IMPELEMENT : can't implement because there is no access to https(secure) url yet
  # Custom Email Verification Route
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::prefix('/email')->group(function () {
      Route::get('/verify', [EmailVerificationController::class, 'verificationNotice'])
        ->middleware(['auth:api', 'throttle:6,1'])
        ->name('verification.notice');
      Route::get('/verify/{id}/{hash}', [EmailVerificationController::class, 'verificationVerify'])
        ->name('verification.verify');
      Route::post('/verification-notification', [EmailVerificationController::class, 'verificationSend'])
        ->middleware(['auth:api', 'throttle:6,1'])
        ->name('verification.send');
      Route::get('/verification-redirect', [EmailVerificationController::class, 'verificationRedirect']);
    });
  });
});
