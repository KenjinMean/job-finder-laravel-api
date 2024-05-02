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
use App\Http\Controllers\Api\UserSkillController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\Api\UserCompanyController;
use App\Http\Controllers\Api\UserEducationController;
use App\Http\Controllers\Api\UserExperienceController;
use App\Http\Controllers\Api\UserCompanyJobController;
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

Route::prefix('auth')->group(function () {
    # Authentication Routes
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
        Route::get('/check-email-availability', [AuthController::class, 'checkEmail'])->name('checkEmail');
    });
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    # OAuth service providers route //SOCIALITE
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
        Route::get('/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
        Route::get('/{provider}/get-authorization-url', [SocialAuthController::class, 'getProviderAuthorizationUrl'])->name('social.getAuthUrl');
    });

    # OTP route
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::post('/request-otp', [OtpController::class, 'requestOtp'])->middleware(['throttle:6,1']);
        Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->middleware(['throttle:6,1']);
    });

    # To FIX: click link on email to verify when already has access to https
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

# JOB ROUTES
Route::prefix('jobs')->group(function () {
    // PUBLIC Job routes.
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('jobs.index');
        Route::get('/search-jobs', [JobController::class, 'searchJobs'])->name('jobs.search');
        Route::get('/search-jobs-suggestions', [JobController::class, 'searchJobSuggestions'])->name('jobs.search-job-suggestions');
        Route::get('/{id}', [JobController::class, 'showById'])->where('id', '[0-9]+')->name('jobs.show-by-id');
        Route::get('/{jobSlug}', [JobController::class, 'showBySlug'])->where('jobSlug', '[a-zA-Z0-9-]+')->name('jobs.show-by-slug');
    });

    // PRIVATE Job routes
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::post('/', [JobController::class, 'store'])->name('jobs.store');
        Route::patch('/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

        # JOBS SKILLS ROUTES
        Route::patch('/{job}/skills', [JobController::class, 'jobSkillsAdd'])->name('jobs.skills.add');
        Route::patch('/{job}/skills/{skill}', [JobController::class, 'jobSkillAdd'])->name('jobs.skill.add');
        Route::delete('/{job}/skills', [JobController::class, 'jobSkillsRemove'])->name('jobs.skills.remove');
        Route::delete('/{job}/skills/{skill}', [JobController::class, 'jobSkillRemove'])->name('jobs.skill.remove');

        # JOBS JOB-TYPES ROUTES
        Route::patch('/{job}/job-types', [JobController::class, 'updateJobTypes'])->name('jobs.update-job-types');

        # JOBS WORK-LOCATION-TYPES ROUTES
        Route::patch('/{job}/work-location-types', [JobController::class, 'updateWorkLocationTypes'])->name('jobs.update-work-location-types');
    });
});


# COMPANY ROUTES
Route::prefix('companies')->group(function () {
    // public companies routes
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
        // define route that can retrieve company jobs
    });

    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::patch('/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::patch('/{company}/company-logo', [CompanyController::class, 'updateCompanyImage'])->name('companies.update-company-image');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::delete('/{company}/company-logo', [CompanyController::class, 'deleteCompanyLogo'])->name('companies.delete-company-logo');
    });
});

# SKILL ROUTES
Route::prefix('skills')->group(function () {
    // public skills routes
    Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
        Route::get('/', [SkillController::class, 'index'])->name('skill.index'); // updated to accept keyword param, can replace "search-skills" endpoint
        Route::get('/search-skills', [SkillController::class, 'searchSkill'])->name('skills.search');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/', [SkillController::class, 'store'])->name('skill.store');
        Route::get('/{skill}', [SkillController::class, 'show'])->name('skill.show');
        Route::patch('/{skill}', [SkillController::class, 'update'])->name('skill.update');
        Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('skill.delete');
    });
});

# USER ROUTES
Route::middleware(['auth:api'])->group(function () {
    Route::prefix('users')->group(function () {

        # USER CONTACTS ROUTES
        Route::prefix('/user-contact')->group(function () {
            Route::get('/', [UserContactController::class, 'index']);
            Route::post('/', [UserContactController::class, 'store']);
            Route::patch('/', [UserContactController::class, 'update']);
            Route::delete('/', [UserContactController::class, 'destroy']);
        });

        # USERS USER-INFO ROUTES
        Route::prefix('/user-info')->group(function () {
            Route::get('/', [UserInfoController::class, 'index']);
            Route::post('/', [UserInfoController::class, 'store']);
            Route::patch('/', [UserInfoController::class, 'update']);
            Route::delete('/', [UserInfoController::class, 'delete']);

            Route::patch('/cover-image', [UserInfoController::class, 'updateCoverImage']);
            Route::patch('/profile-image', [UserInfoController::class, 'updateProfileImage']);

            Route::delete('/cover-image', [UserInfoController::class, 'deleteCoverImage']);
            Route::delete('/profile-image', [UserInfoController::class, 'deleteProfileImage']);
        });

        # USERS SKILLS ROUTES
        Route::prefix('/skills')->group(function () {
            Route::get('/', [UserSkillController::class, 'getUserSkills']);
            Route::post('/{skill}', [UserSkillController::class, 'addUserSkill']);
            Route::post('/', [UserSkillController::class, 'addUserSkills']);
            Route::delete('/{skill}', [UserSkillController::class, 'removeUserSkill']);
            Route::delete('/', [UserSkillController::class, 'removeUserSkills']);
        });

        # USERS EXPERIENCES ROUTES
        Route::apiResource('user-experiences', UserExperienceController::class);

        # USERS EDUCATIONS ROUTES Rotues
        Route::apiResource('user-educations', UserEducationController::class);

        // Routes for managing USER companies #USERS COMPANIES ROUTES
        Route::prefix('/companies')->group(function () {
            Route::get('/', [UserCompanyController::class, 'index']);
            Route::get('/{company}', [UserCompanyController::class, 'show']);
            Route::post('/', [UserCompanyController::class, 'store']);
            Route::patch('/{company}', [UserCompanyController::class, 'update']);
            Route::delete('/{company}', [UserCompanyController::class, 'delete']);
            Route::patch('/{company}/company-logo', [UserCompanyController::class, 'updateCompanyLogo']);
            Route::delete('/{company}/company-logo', [UserCompanyController::class, 'deleteCompanyLogo']);

            // Routes for managing company jobs #USER COMPANY JOB
            Route::prefix('/{company}/jobs')->group(function () {
                Route::get('/', [UserCompanyJobController::class, 'index']);
                Route::get('/{job}', [UserCompanyJobController::class, 'show']);
                Route::post('/', [UserCompanyJobController::class, 'store']);
                Route::patch('/{job}', [UserCompanyJobController::class, 'update']);
                Route::delete('/{job}', [UserCompanyJobController::class, 'delete']);

                // Routes for managing job skills #USER COMPANY JOB SKILL
                Route::prefix('/{job}/skills')->group(function () {
                    Route::patch('/', [UserCompanyJobController::class, 'jobSkillsAdd']);
                    Route::patch('/{skill}', [UserCompanyJobController::class, 'jobSkillAdd']);
                    Route::delete('/', [UserCompanyJobController::class, 'jobSkillsRemove']);
                    Route::delete('/{skill}', [UserCompanyJobController::class, 'jobSkillRemove']);
                });

                // Routes for managing job types #USER COMPANY JOB JOB_TYPE
                Route::patch('/{job}/job-types', [UserCompanyJobController::class, 'updateJobTypes']);

                // Routes for managing work location types #USER COMPANY JOB WORK_LOCATION_TYPE
                Route::patch('/{job}/work-location-types', [UserCompanyJobController::class, 'updateWorkLocationTypes']);
            });
        });

        // USERS RESOURCE ROUTES
        // UPDATE: make a public user route that anyone can view
        Route::patch('/update-password', [UserController::class, 'updateUserPassword']);
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::patch('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware(['verified']);
    });
});
