<?php

namespace App\Services;

use App\Models\Skill;
use App\Http\Resources\SkillResource;

class SkillService {
  public function getSkills() {
    return SkillResource::collection(Skill::all());
  }

  public function createSkill($validatedRequest): void {
    Skill::create($validatedRequest);
  }

  public function showSkill($skillId) {
    return Skill::findOrFail($skillId);
  }

  public function updateSkill($skill, $validatedRequest): void {
    $skill->update($validatedRequest);
  }

  public function deleteSkill($skill): void {
    $skill->delete();
  }

  public function searchSkill($keyword) {
    $skills = Skill::where('name', 'like', "%{$keyword}%")
      ->orderBy('created_at', 'desc')
      ->get();

    return SkillResource::collection($skills);
  }
}
