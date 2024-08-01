<?php

/*
|--------------------------------------------------------------------------
| jobType API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for jobType-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Middleware\CheckTokenExpiration;
use App\Http\Controllers\Api\JobTypeController;

Route::prefix('job-types')->group(function () {
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/', [JobTypeController::class, 'index'])->name('jobTypes.index');
  });
});
