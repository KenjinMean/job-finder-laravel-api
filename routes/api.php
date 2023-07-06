<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\SkillController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('skills', [SkillController::class, 'index']);

    # User routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show', [UserController::class, 'show'])->name('user.show');
        Route::put('/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/destroy', [UserController::class, 'destroy'])->name('user.destroy');
        Route::patch('/update-skills', [UserController::class, 'updateSkill'])->name('user.update-skill');
    });

    # User Info routes
    Route::prefix('user-infos')->group(function () {
        Route::get('/', [UserInfoController::class, 'index'])->name('user-infos.index');
        Route::post('/store', [UserInfoController::class, 'store'])->name('user-infos.store');
        Route::get('/show', [UserInfoController::class, 'show'])->name('user-infos.show');
        Route::put('/update', [UserInfoController::class, 'update'])->name('user-infos.update');
        Route::delete('/delete', [UserInfoController::class, 'destroy'])->name('user-infos.destroy');
        Route::patch('/update-profile', [UserInfoController::class, 'updateProfile'])->name('user-infos.update-profile');
        Route::patch('/update-cover', [UserInfoController::class, 'updateCover'])->name('user-infos.update-cover');
    });

    # Company routes
    Route::get('companies/user-companies', [CompanyController::class, 'getCompany'])->name('companies.user-companies');
    Route::apiResource('companies', CompanyController::class);

    # Job routes
    Route::get('/jobs/get-jobs', [JobController::class, 'getJobs']);
    Route::patch('/jobs/update-job-skills/{job}', [JobController::class, 'updateJobSkills']);
    Route::get('/jobs/search-jobs', [JobController::class, 'searchJobs']);
    Route::apiResource('/jobs', JobController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
