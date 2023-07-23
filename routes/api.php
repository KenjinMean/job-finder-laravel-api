<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserInfoController;

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
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware(['auth:api', 'check.token.expiration'])
    ->name('logout');

# OAuth service providers route :: SOCIALITE
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

# Custom Email Verification Route
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

# DEVELOPER ROUTES
Route::get('skills', [SkillController::class, 'index']);
Route::apiResource('skills', SkillController::class);

# JOB ROUTES
Route::get('/jobs/get-jobs', [JobController::class, 'getJobs']);
Route::get('/jobs/search-jobs', [JobController::class, 'searchJobs']);
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('job.show');
Route::middleware(['auth:api', 'verified', 'check.token.expiration'])->group(function () {
    Route::post('/jobs', [JobController::class, 'store'])->name('job.store');
    Route::patch('/jobs/update-job-skills/{job}', [JobController::class, 'updateJobSkills']);
    Route::patch('/jobs/update-skills', [JobController::class, 'updateSkill'])->name('job.update-skill');
    Route::patch('/jobs/{job}', [JobController::class, 'update'])->name('job.update');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('job.destroy');
    // Route::apiResource('/jobs', JobController::class);
});

# COMPANY ROUTES
// Route::apiResource('companies', CompanyController::class);
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('company.show');
Route::middleware(['auth:api', 'verified', 'check.token.expiration'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('companies/user-companies', [CompanyController::class, 'showUserCompanies'])->name('companies.user-companies');
    Route::patch('companies/update-company-image/{company}', [CompanyController::class, 'updateCompanyImage'])->name('companies.update-company-image');
    Route::post('/companies', [CompanyController::class, 'store'])->name('company.store');
    Route::patch('/companies/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');
});

# PROTECTED GROUPED ROUTES
Route::middleware(['auth:api', 'verified', 'check.token.expiration'])->group(function () {
    # USER ROUTES
    Route::prefix('users')->group(function () {
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show', [UserController::class, 'show'])->name('user.show');
        Route::patch('/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/destroy', [UserController::class, 'destroy'])->name('user.destroy');
        Route::patch('/update-skills', [UserController::class, 'updateSkill'])->name('user.update-skill');
    });

    # SKILL ROUTES
    Route::get('search-skills', [SkillController::class, 'searchSkill'])->name('skills.search-skill');

    # USER INFO ROUTES
    Route::prefix('user-infos')->group(function () {
        Route::get('/', [UserInfoController::class, 'index'])->name('user-infos.index');
        Route::post('/store', [UserInfoController::class, 'store'])->name('user-infos.store');
        Route::get('/show', [UserInfoController::class, 'show'])->name('user-infos.show');
        Route::put('/update', [UserInfoController::class, 'update'])->name('user-infos.update');
        Route::delete('/delete', [UserInfoController::class, 'destroy'])->name('user-infos.destroy');
        Route::patch('/update-profile-image', [UserInfoController::class, 'updateProfileImage'])->name('user-infos.update-profile');
        Route::patch('/update-cover-image', [UserInfoController::class, 'updateCoverImage'])->name('user-infos.update-cover');
    });
});
