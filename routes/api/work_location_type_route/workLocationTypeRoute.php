<?php

/*
|--------------------------------------------------------------------------
| workLocationType API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for workLocationType-related functionalities
| in your application.
|
*/

use App\Http\Controllers\Api\WorkLocationTypeController;
use App\Models\WorkLocationType;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckTokenExpiration;

Route::prefix('work-location-types')->group(function () {
  Route::withoutMiddleware([CheckTokenExpiration::class])->group(function () {
    Route::get('/', [WorkLocationTypeController::class, 'index'])->name('workLocationType.index');
  });
});
