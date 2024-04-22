<?php

namespace App\Http\Controllers\api;

use App\Models\Skill;
use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Requests\ManageSkillRequest;

class UserSkillController extends Controller {
    /** ------------------------------------------------------------------ */
    public function getUserSkills() {
        $user = JwtHelper::getUserFromToken();

        return response()->json(["skills" => SkillResource::collection($user->skills)], Response::HTTP_OK);
    }

    // Method to add a single skill to a user.
    // Accepts a skill ID as a URL parameter. e.g. POST:api/users/skills/4
    /** ------------------------------------------------------------------ */
    public function addUserSkill(Skill $skill) {
        $user = JwtHelper::getUserFromToken();
        $user->skills()->syncWithoutDetaching([$skill->id]);

        return response()->json(['message' => 'Skill added to user successfully'], Response::HTTP_OK);
    }

    // Method to add multiple skills to a user.
    // Accepts a skill_ids array that contains skill IDs in the request body, like this { "skill_ids": ["9", "10"] }.
    /** ------------------------------------------------------------------ */
    public function addUserSkills(ManageSkillRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $skillIds = $request->input('skill_ids');
        $user->skills()->syncWithoutDetaching($skillIds);

        return response()->json(['message' => 'Skills added to user successfully'], Response::HTTP_OK);
    }

    // Method to removes single skill to user.
    // Accepts a skill ID as a URL parameter. e.g. DELETE:api/users/skills/4
    /** ------------------------------------------------------------------ */
    public function removeUserSkill(Skill $skill) {
        $user = JwtHelper::getUserFromToken();
        $user->skills()->detach($skill);

        return response()->json(["message" => "Skill removed successfully"], Response::HTTP_OK);
    }

    // method to removes multiple skills
    // accepts skill_ids array that contains skill ids like this { "skill_ids": ["9", "10"] }
    /** ------------------------------------------------------------------ */
    public function removeUserSkills(ManageSkillRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $skillIds = $request->input('skill_ids');
        $user->skills()->detach($skillIds);

        return response()->json(["message" => "Skills removed successfully"], Response::HTTP_OK);
    }
}
