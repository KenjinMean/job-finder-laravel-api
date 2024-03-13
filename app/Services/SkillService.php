<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SkillResource;

class SkillService {
  public function getSkills() {
    return SkillResource::collection(Skill::all());
  }

  public function showSkill($skillId) {
    return Skill::findOrFail($skillId);
  }

  public function createSkill($validatedRequest): void {
    Skill::create($validatedRequest);
  }

  public function updateSkill($user, $skillId) {
    $userId = $user->id;
    return response()->json(["skill" => $skillId, "user" => $userId]);
  }

  public function deleteSkill($skill): void {
    $skill->delete();
  }

  public function searchSkill($keyword) {
    if (empty($keyword) || is_null($keyword) || strlen($keyword) < 2) {
      // If the keyword is empty, null, or less than 2 characters, return 10 suggestions skills
      $skills = Skill::orderBy('created_at', 'desc')->take(10)->get();
    } else {
      // If the keyword is provided and has at least 2 characters, perform the search
      $skills = Skill::where('name', 'like', "%{$keyword}%")
        ->orderBy('created_at', 'desc')
        ->get();
    }
    return SkillResource::collection($skills);
  }
}
