<?php

namespace App\Http\Controllers\Api;

use App\Models\Skill;
use App\Helpers\JwtHelper;
use App\Services\SkillService;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddSkillRequest;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\SearchSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SkillController extends Controller {

    private $skillService;

    public function __construct(SkillService $skillService) {
        $this->skillService = $skillService;
    }

    public function index() {
        try {
            $this->authorize('viewAll', Skill::class);
            return $this->skillService->getSkills();
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function store(StoreSkillRequest $request) {
        try {
            $validatedRequest = $request->validated();
            $this->authorize('create');
            $this->skillService->createSkill($validatedRequest);
            return response()->json(['message' => "Skill created successfully"]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function show($skillId) {
        try {
            $skill =  $this->skillService->showSkill($skillId);
            return response()->json($skill);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    // public function update(UpdateSkillRequest $request, $skillId) {
    //     try {
    //         $skill = Skill::findOrFail($skillId);
    //         $validatedRequest = $request->validated();
    //         $this->authorize('update', $skill);
    //         $this->skillService->updateSkill($skill, $validatedRequest);
    //         return response()->json(["message" => "Skill updated successfully"]);
    //     } catch (\Throwable $e) {
    //         return ExceptionHelper::handleException($e);
    //     }
    // }

    public function destroy($skillId) {
        try {
            $skill = Skill::findOrFail($skillId);
            $this->authorize('delete', $skill);
            $this->skillService->deleteSkill($skill);
            return response()->json(["message" => "Skill deleted successfully"]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function getUserSkills() {
        try {
            $user = JwtHelper::getUserFromToken();
            $skill = $this->skillService->getUserSkills($user);
            return response()->json(["skills" => $skill]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function searchSkill(SearchSkillRequest $request) {
        try {
            $keyword = $request->validated()['keyword'];
            $skill = $this->skillService->searchSkill($keyword);
            return response()->json(["skills" => $skill]);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function addSkill(AddSkillRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $skillId = $request->validated()['skill_id'];
            return $this->skillService->addSkill($user, $skillId);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function removeSkill(AddSkillRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $skillId = $request->validated()['skill_id'];
            return $this->skillService->removeSkill($user, $skillId);
        } catch (\Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
