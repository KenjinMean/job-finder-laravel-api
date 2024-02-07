<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Middleware\CheckTokenExpiration;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserInfoController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\Api\UserEducationController;
use App\Http\Controllers\Api\UserExperienceController;

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
    Route::get('/auth/{provider}/get-authorization-url', [SocialAuthController::class, 'getProviderAuthorizationUrl'])->name('social.callback');
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

# SKILL ROUTES
Route::get('skills', [SkillController::class, 'index']);
Route::apiResource('skills', SkillController::class);

# JOB ROUTES
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

# USER ROUTES
# UNVERIFIED PROTRECTED USER ROUTES
Route::middleware(['auth:api'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show', [UserController::class, 'show'])->name('user.show');
    });
});

# VERIFIED PROTRECTED USER ROUTES
Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::patch('/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('users/destroy', [UserController::class, 'destroy'])->middleware(['auth:api'])->name('user.destroy');
    });
});

//PUBLIC USER INFO ROUTES

# PRIVATE USER INFO ROUTES

# UNVERIFIED USER-INFO ROUTES
Route::middleware(['auth:api'])->group(function () {
    Route::prefix('user-infos')->group(function () {
        Route::get('/show', [UserInfoController::class, 'show'])->name('user-infos.show');
        Route::patch('/update', [UserInfoController::class, 'update'])->name('user-infos.update');
        Route::patch('/update-profile-image', [UserInfoController::class, 'updateProfileImage'])->name('user-infos.update-profile');
        Route::patch('/update-cover-image', [UserInfoController::class, 'updateCoverImage'])->name('user-infos.update-cover');
    });

    # SKILLS ROUTES
    Route::get('get-user-skills', [SkillController::class, 'getUserSkills'])->name('skills.get-user-skills');
    Route::get('search-skills', [SkillController::class, 'searchSkill'])->name('skills.search-skill');
    Route::post('add-skill', [SkillController::class, 'addSkill'])->name('skills.update-skill');
    Route::delete('remove-skill', [SkillController::class, 'removeSkill'])->name('skills.remove-skill');

    # USER INFOS ROUTES
    Route::prefix('user-infos')->group(function () {
        Route::post('/store', [UserInfoController::class, 'store'])->name('user-infos.store');
        Route::delete('/delete', [UserInfoController::class, 'destroy'])->name('user-infos.destroy');
    });

    # USER CONTACTS ROUTES
    Route::prefix('user-contacts')->group(function () {
        Route::get('/show', [UserContactController::class, 'show'])->name('user-contact.show');
        Route::patch('/update', [UserContactController::class, 'update'])->name('user-contact.update');
    });

    # USER EXPERIENCES ROUTES
    Route::prefix('user-experiences')->group(function () {
        Route::get('/show', [UserExperienceController::class, 'show'])->name('user-contact.show');
        // Route::patch('/update', [UserExperienceController::class, 'update'])->name('user-contact.update');
    });

    # USER EDUCATIONS ROUTES
    Route::prefix('user-educations')->group(function () {
        Route::get('/show', [UserEducationController::class, 'show'])->name('user-education.show');
        // Route::patch('/update', [UserExperienceController::class, 'update'])->name('user-contact.update');
    });
});

# VERIFIED USER-INFO ROUTES
Route::middleware(['auth:api', 'verified'])->group(function () {
});

// USER CONTACT ROUTES
