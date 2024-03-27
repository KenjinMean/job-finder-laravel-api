<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Middleware\CheckTokenExpiration;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserInfoController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\Api\UserEducationController;
use App\Http\Controllers\Api\UserExperienceController;
use App\Http\Controllers\Api\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# Authentication Routes
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    // Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('register-user', [AuthController::class, 'registerUser'])->name('register-user');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
    Route::get('check-email-availability', [AuthController::class, 'checkEmail'])->name('checkEmail');
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});

# OAuth service providers route //SOCIALITE
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
    Route::get('/auth/{provider}/get-authorization-url', [SocialAuthController::class, 'getProviderAuthorizationUrl'])->name('social.getAuthUrl');
});

# OTP route
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::post('/request-otp', [OtpController::class, 'requestOtp'])->middleware(['throttle:6,1']);
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->middleware(['throttle:6,1']);
});

# Custom Email Verification Route
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::prefix('email')->group(function () {
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

# JOB ROUTES
// convert to RESTful api
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/jobs/get-job-posting', [JobController::class, 'getJobPostings'])->name('job.get-job-posting');
    Route::get('/jobs/search-jobs', [JobController::class, 'searchJobs'])->name('job.search');
    Route::get('/jobs/search-jobs-suggestions', [JobController::class, 'searchJobSuggestions'])->name('job.search-job-suggestions');
    Route::get('/jobs/filter-jobs', [JobController::class, 'filterJobs'])->name('job.filter');
    Route::post('/jobs/update-job-type/{job}', [JobController::class, 'updateJobType'])->name('job.update-job-type');
    Route::get('/jobs/{jobSlug}', [JobController::class, 'show'])->name('job.show'); //this uses slug to get the jobs
    // Route::get('/jobs/{job}', [JobController::class, 'show'])->name('job.show'); //this uses job id to get the jobs
});

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::post('/jobs', [JobController::class, 'store'])->name('job.store');
    Route::patch('/jobs/update-job-skills/{job}', [JobController::class, 'updateJobSkills'])->name('job.update-job-skill');
    Route::patch('/jobs/{job}', [JobController::class, 'update'])->name('job.update');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('job.destroy');
});

# COMPANY ROUTES
// convert to RESTful api
Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::get('companies/user-companies', [CompanyController::class, 'showUserCompanies'])->name('companies.user-companies');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/companies', [CompanyController::class, 'store'])->name('company.store');
    Route::patch('/companies/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');
    Route::patch('companies/update-company-image/{company}', [CompanyController::class, 'updateCompanyImage'])->name('companies.update-company-image');
});

Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('company.show');
});

# SKILL ROUTES
Route::middleware(['auth:api'])->group(function () {
    Route::get('skills/search-skills', [SkillController::class, 'searchSkill'])->name('skills.search');
    Route::prefix('skills')->group(function () {
        Route::get('/', [SkillController::class, 'index'])->name('skill.index');
        Route::post('/', [SkillController::class, 'store'])->name('skill.store');
        Route::get('/{skill}', [SkillController::class, 'show'])->name('skill.show');
        Route::patch('/{skill}', [SkillController::class, 'update'])->name('skill.update');
        Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('skill.delete');
    });
});

# USER ROUTES
Route::middleware(['auth:api'])->group(function () {
    # USER ROUTES
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware(['verified'])->name('users.destroy');

        // User Skill Manager Routes
        Route::get('/{user}/skills', [UserController::class, 'getUserSkills'])->name('users.skills');
        Route::post('/{user}/skills/{skill}', [UserController::class, 'addUserSkill'])->name('users.add-skill');
        Route::post('/{user}/skills', [UserController::class, 'addUserSkills'])->name('users.add-skills');
        Route::delete('/{user}/skills/{skill}', [UserController::class, 'removeUserSkill'])->name('users.remove-skill');
        Route::delete('/{user}/skills', [UserController::class, 'removeUserSkills'])->name('users.remove-skills');
    });

    # USER-INFO ROUTES
    Route::prefix('user-info')->group(function () {
        Route::get('/', [UserInfoController::class, 'index'])->name('user-info.index');
        Route::patch('/', [UserInfoController::class, 'update'])->name('user-info.update');
        Route::patch('/cover-image', [UserInfoController::class, 'updateCoverImage'])->name('user-info.update-cover');
        Route::patch('/profile-image', [UserInfoController::class, 'updateProfileImage'])->name('user-info.update-profile-image');
    });

    # USER CONTACTS ROUTES
    Route::prefix('user-contact')->group(function () {
        Route::get('/', [UserContactController::class, 'index'])->name('user-contact.index');
        Route::post('/', [UserContactController::class, 'store'])->name('user-contact.store');
        Route::patch('/', [UserContactController::class, 'update'])->name('user-contact.update');
        Route::delete('/', [UserContactController::class, 'destroy'])->name('user-contact.delete');
    });

    # USER EXPERIENCES ROUTES
    Route::apiResource('user-experiences', UserExperienceController::class);

    # USER EDUCATIONS ROUTES Rotues
    Route::apiResource('user-educations', UserEducationController::class);
});
