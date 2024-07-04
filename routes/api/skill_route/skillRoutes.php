<?php

/*
|--------------------------------------------------------------------------
| skill API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for skill-related functionalities
| in your application.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillController;
use App\Http\Middleware\CheckTokenExpiration;

// NOTE: do not rearange routes to prevent route conflict

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
