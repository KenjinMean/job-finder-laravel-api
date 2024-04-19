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

// NOTE: do not rearange routes to prevent route conflict

# Authentication Routes
Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
    Route::get('check-email-availability', [AuthController::class, 'checkEmail'])->name('checkEmail');
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
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
Route::prefix('jobs')->group(function () {
    // public Job routes.
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('jobs.index');
        Route::get('/search-jobs', [JobController::class, 'searchJobs'])->name('jobs.search');
        Route::get('/search-jobs-suggestions', [JobController::class, 'searchJobSuggestions'])->name('jobs.search-job-suggestions');
        Route::get('/{id}', [JobController::class, 'showById'])->where('id', '[0-9]+')->name('jobs.show-by-id');
        Route::get('/{jobSlug}', [JobController::class, 'showBySlug'])->where('jobSlug', '[a-zA-Z0-9-]+')->name('jobs.show-by-slug');
    });


    // private Job routes
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::post('/', [JobController::class, 'store'])->name('jobs.store');
        Route::patch('/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

        Route::patch('/{job}/skills', [JobController::class, 'jobSkillsAdd'])->name('jobs.skills.add');
        Route::patch('/{job}/skills/{skill}', [JobController::class, 'jobSkillAdd'])->name('jobs.skill.add');
        Route::delete('/{job}/skills', [JobController::class, 'jobSkillsRemove'])->name('jobs.skills.remove');
        Route::delete('/{job}/skills/{skill}', [JobController::class, 'jobSkillRemove'])->name('jobs.skill.remove');

        Route::patch('/{job}/job-types', [JobController::class, 'updateJobTypes'])->name('jobs.update-job-types');

        Route::patch('/{job}/work-location-types', [JobController::class, 'updateWorkLocationTypes'])->name('jobs.update-work-location-types');
    });
});


# COMPANY ROUTES
Route::prefix('companies')->group(function () {
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
    });

    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::patch('/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::patch('/{company}/company-image', [CompanyController::class, 'updateCompanyImage'])->name('companies.update-company-image');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::delete('/{company}/company-logo', [CompanyController::class, 'deleteCompanyLogo'])->name('companies.delete-company-logo');
    });
});

# SKILL ROUTES
Route::middleware(['auth:api'])->group(function () {
    // do not put this route inside skill prefix routes to avoid conflict
    // make search skill a non auth routes
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
    // make public user route that anyone can view
    // refactor to remove injecting user id to route and use token instead
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

        // to implement , make user related routes under user prefix
        Route::prefix('/{user}/user-info')->group(function () {
            Route::get('/', [UserInfoController::class, 'index'])->name('users.user-info.index');
            Route::patch('/{user-info}', [UserInfoController::class, 'update'])->name('user.user-info.update');
        });
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


#VERSION 2 ROUTES
Route::prefix('v2')->group(function () {
    Route::prefix('users')->group(function () {
        // Users Resource
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::patch('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware(['verified']);

        // Users Job
        Route::get('/jobs', [UserController::class, 'index']);

        // make a UserSkillController
        Route::get('/skills', [UserController::class, 'getUserSkills']);
        Route::post('/skills/{skill}', [UserController::class, 'addUserSkill']);
        Route::post('/skills', [UserController::class, 'addUserSkills']);
        Route::delete('/skills/{skill}', [UserController::class, 'removeUserSkill']);
        Route::delete('/skills', [UserController::class, 'removeUserSkills']);
    });
});
