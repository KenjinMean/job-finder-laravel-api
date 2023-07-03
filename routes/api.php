<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserInfoController;
use App\Http\Controllers\Api\UserSkillController;

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

    Route::apiResource('/jobs', JobController::class);
    Route::apiResource('/category', CategoryController::class);

    Route::post('set-skills', [UserSkillController::class, 'setSkill']);


    #user routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show', [UserController::class, 'show'])->name('user.show');
        Route::put('/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/destroy', [UserController::class, 'destroy'])->name('user.destroy');
    });

    #user Company routes
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/store', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/show', [CompanyController::class, 'show'])->name('companies.show');
        Route::put('/update', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/destroy', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::get('/user-companies', [CompanyController::class, 'getCompany'])->name('companies.user-companies');
    });

    #user Info routes
    Route::prefix('user-infos')->group(function () {
        Route::get('/', [UserInfoController::class, 'index'])->name('user-infos.index');
        Route::post('/store', [UserInfoController::class, 'store'])->name('user-infos.store');
        Route::get('/show', [UserInfoController::class, 'show'])->name('user-infos.show');
        Route::put('/update', [UserInfoController::class, 'update'])->name('user-infos.update');
        Route::delete('/delete', [UserInfoController::class, 'destroy'])->name('user-infos.destroy');
        Route::patch('/update-profile', [UserInfoController::class, 'updateProfile'])->name('user-infos.update-profile');
        Route::patch('/update-cover', [UserInfoController::class, 'updateCover'])->name('user-infos.update-cover');
    });

    Route::post('logout', [AuthController::class, 'logout']);
});
