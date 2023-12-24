<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;
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

  // public function updateSkill($skill, $validatedRequest): void {
  //   $skill->update($validatedRequest);
  // }

  public function deleteSkill($skill): void {
    $skill->delete();
  }

  public function getUserSkills($user) {
    $skills = $user->skills;
    return SkillResource::collection($skills);
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

  public function updateSkill($user, $skillId) {
    $userId = $user->id;
    return response()->json(["skill" => $skillId, "user" => $userId]);
  }

  public function addSkill($user, $skillId) {
    try {

      if ($user->skills()->where('skill_id', $skillId)->exists()) {

        throw new \Exception('Skill is already associated with the user.');
      }

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

  // remove skills based on array of skills provided
  public function removeSkills($user, $skillIds) {
    try {
      DB::beginTransaction();

      foreach ($skillIds as $skillId) {
        Skill::findOrFail($skillId);
        $user->skills()->detach($skillId);
      }

      DB::commit();

      return response()->json(["message" => "Skills removed successfully"]);
    } catch (\Throwable $e) {
      DB::rollBack();
      throw new \Exception($e->getMessage());
    }
  }
}
