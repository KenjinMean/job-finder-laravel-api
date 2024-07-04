<?php

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for user-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserInfoController;
use App\Http\Controllers\Api\SavedJobController;
use App\Http\Controllers\api\UserSkillController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\api\UserCompanyController;
use App\Http\Controllers\Api\UserEducationController;
use App\Http\Controllers\Api\UserExperienceController;
use App\Http\Controllers\api\UserCompanyJobController;

// NOTE: do not rearange routes to prevent route conflict

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

    # USERS SAVED JOBS ROUTES
    Route::prefix('/save-jobs')->group(function () {
      Route::get('/', [SavedJobController::class, 'index']);
      Route::post('/{job}', [SavedJobController::class, 'store']);
      Route::delete('/{job}', [SavedJobController::class, 'delete']);
    });

    #USERS COMPANIES ROUTES
    // Routes for managing USER companies 
    Route::prefix('/companies')->group(function () {
      Route::get('/', [UserCompanyController::class, 'index']);
      Route::get('/{company}', [UserCompanyController::class, 'show']);
      Route::post('/', [UserCompanyController::class, 'store']);
      Route::patch('/{company}', [UserCompanyController::class, 'update']);
      Route::delete('/{company}', [UserCompanyController::class, 'delete']);
      Route::patch('/{company}/company-logo', [UserCompanyController::class, 'updateCompanyLogo']);
      Route::delete('/{company}/company-logo', [UserCompanyController::class, 'deleteCompanyLogo']);

      #USERS COMPANY JOB
      // Routes for managing company jobs 
      Route::prefix('/{company}/jobs')->group(function () {
        Route::get('/', [UserCompanyJobController::class, 'index']);
        Route::get('/{job}', [UserCompanyJobController::class, 'show']);
        Route::post('/', [UserCompanyJobController::class, 'store']);
        Route::patch('/{job}', [UserCompanyJobController::class, 'update']);
        Route::delete('/{job}', [UserCompanyJobController::class, 'delete']);

        #USER COMPANY JOB SKILL
        // Routes for managing job skills 
        Route::prefix('/{job}/skills')->group(function () {
          Route::patch('/', [UserCompanyJobController::class, 'jobSkillsAdd']);
          Route::patch('/{skill}', [UserCompanyJobController::class, 'jobSkillAdd']);
          Route::delete('/', [UserCompanyJobController::class, 'jobSkillsRemove']);
          Route::delete('/{skill}', [UserCompanyJobController::class, 'jobSkillRemove']);
        });

        #USER COMPANY JOB JOB_TYPE
        // Routes for managing job types 
        Route::patch('/{job}/job-types', [UserCompanyJobController::class, 'updateJobTypes']);

        #USER COMPANY JOB WORK_LOCATION_TYPE
        // Routes for managing work location types 
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
