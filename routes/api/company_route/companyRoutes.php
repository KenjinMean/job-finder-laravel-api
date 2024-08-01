<?php

/*
|--------------------------------------------------------------------------
| company API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for company-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckTokenExpiration;
use App\Http\Controllers\Api\CompanyController;

// NOTE: do not rearange routes to prevent route conflict

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
    Route::delete('/{company}/company-logo', [CompanyController::class, 'deleteCompanyImage'])->name('companies.delete-company-logo');
  });
});
