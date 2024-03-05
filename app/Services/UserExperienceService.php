<?php

namespace App\Services;

use App\Models\UserWorkExperience;
use App\Http\Resources\UserExperienceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserExperienceService {
  /* ----------------------------------------------------------- */
  public function index($user): AnonymousResourceCollection {
    $userExperiences = $user->userWorkExperiences()->with('skills')->get();
    return UserExperienceResource::collection($userExperiences);
  }

  /* ----------------------------------------------------------- */
  public function show($experienceId): UserExperienceResource {
    $userExperience = UserWorkExperience::with('skills')->findOrFail($experienceId);

    return new UserExperienceResource($userExperience);
  }

  /* ----------------------------------------------------------- */
  public function store($data, $user) {
    $skillIds = $data['skillIds'] ?? [];
    unset($data['skillIds']);

    $userSkills = $user->skills()->pluck('skills.id')->toArray();

    $data['user_id'] = $user->id;
    $userExperience = UserWorkExperience::create($data);

    // attach skill not associated with the user
    foreach ($skillIds as $skillId) {
      $userExperience->skills()->attach($skillId);

      if (!in_array($skillId, $userSkills)) {
        $user->skills()->attach($skillId);
      }
    }
  }

  /* ----------------------------------------------------------- */
  public function update($data, $user) {
    $experienceId = $data['id'];
    $skillIds = $data['skillIds'] ?? [];

    $userExperience = UserWorkExperience::findOrFail($experienceId);

    // Update the user work experience data
    $userExperience->update($data);

    // Sync the skills for the user work experience
    $userExperience->skills()->sync($skillIds);

    // Attach new skills to the user work experience
    foreach ($skillIds as $skillId) {
      $userExperience->skills()->syncWithoutDetaching($skillId);

      // Check if the skill is not associated with the user and attach it
      if (!$user->skills()->where('skills.id', $skillId)->exists()) {
        $user->skills()->attach($skillId);
      }
    }

    return response()->json(["message" => "User experience updated successfully"]);
  }

  /* ----------------------------------------------------------- */
  public function destroy($userExperienceId) {
    $userEducation = UserWorkExperience::findOrFail($userExperienceId);
    $userEducation->delete();
  }
}
