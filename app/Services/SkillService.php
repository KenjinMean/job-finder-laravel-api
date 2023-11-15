<?php

namespace App\Services;

use App\Models\User;
use App\Models\Skill;
use App\Http\Resources\SkillResource;
use Error;

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

  // public function updateSkill($skill, $validatedRequest): void {
  //   $skill->update($validatedRequest);
  // }

  public function deleteSkill($skill): void {
    $skill->delete();
  }

  public function searchSkill($keyword) {
    $skills = Skill::where('name', 'like', "%{$keyword}%")
      ->orderBy('created_at', 'desc')
      ->get();

    return SkillResource::collection($skills);
  }

  public function updateSkill($user, $skillId) {
    $userId = $user->id;
    return response()->json(["skill" => $skillId, "user" => $userId]);
  }

  public function addSkill($user, $skillId) {
    try {
      Skill::findOrFail($skillId);
      $user->skills()->attach($skillId);

      return response()->json(["message" => "Skills added successfully"]);
    } catch (\Throwable $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function removeSkill($user, $skillId) {
    try {
      Skill::findOrFail($skillId);
      $user->skills()->detach($skillId);

      return response()->json(["message" => "Skills removed successfully"]);
    } catch (\Throwable $e) {
      throw new \Exception($e->getMessage());
    }
  }
}
