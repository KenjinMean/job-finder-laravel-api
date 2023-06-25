<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\UserController;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('users/company', [UserController::class, 'getCompany']);

    Route::apiResource('/jobs', JobController::class);
    Route::apiResource('/company', CompanyController::class);
    Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/users', UserController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
