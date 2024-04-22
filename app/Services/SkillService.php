<?php

namespace App\Services;

use App\Models\Skill;
use App\Http\Resources\SkillResource;

class SkillService {
  public function index($validatedRequest) {
    $keyword = $validatedRequest['keyword'] ?? '';

    $query = Skill::query();

    // If keyword is provided and has at least 2 characters, perform search
    if (!empty($keyword) && strlen($keyword) >= 2) {
      $query->where('name', 'like', "%{$keyword}%");
    }

    $skills = $query->orderBy('created_at', 'desc')->paginate(10);

    return SkillResource::collection($skills);
  }

  /** ------------------------------------------------------------------ */
  public function show($skillId) {
    return Skill::findOrFail($skillId);
  }

  /** ------------------------------------------------------------------ */
  public function store($validatedRequest): void {
    Skill::create($validatedRequest);
  }

  /** ------------------------------------------------------------------ */
  public function update($user, $skillId) {
    $userId = $user->id;
    return response()->json(["skill" => $skillId, "user" => $userId]);
  }

  /** ------------------------------------------------------------------ */
  public function delete($skill): void {
    $skill->delete();
  }
}
