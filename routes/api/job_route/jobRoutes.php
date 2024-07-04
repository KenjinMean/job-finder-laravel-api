<?php

/*
|--------------------------------------------------------------------------
| job API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for job-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Middleware\CheckTokenExpiration;

// NOTE: do not rearange routes to prevent route conflict

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
