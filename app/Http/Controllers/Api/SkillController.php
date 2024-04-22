<?php

namespace App\Http\Controllers\Api;

use App\Models\Skill;
use App\Services\SkillService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\SearchSkillRequest;
use App\Http\Requests\UpdateSkillRequest;

class SkillController extends Controller {

    private $skillService;

    public function __construct(SkillService $skillService) {
        $this->skillService = $skillService;
    }

    /** ------------------------------------------------------------------ */
    public function index(SearchSkillRequest $request) {
        $validatedRequest = $request->validated();
        return $this->skillService->index($validatedRequest);
    }

    /** ------------------------------------------------------------------ */
    public function store(StoreSkillRequest $request) {
        $validatedRequest = $request->validated();
        $this->authorize('create');
        $this->skillService->store($validatedRequest);

        return response()->json(['message' => "Skill created successfully"]);
    }

    /** ------------------------------------------------------------------ */
    public function show(Skill $skill) {
        $this->authorize('viewAll', Skill::class);

        return response()->json($skill);
    }

    /** ------------------------------------------------------------------ */
    public function update(UpdateSkillRequest $request, $skillId) {
        $skill = Skill::findOrFail($skillId);
        $validatedRequest = $request->validated();
        $this->authorize('update', $skill);
        $this->skillService->update($skill, $validatedRequest);

        return response()->json(["message" => "Skill updated successfully"]);
    }

    /** ------------------------------------------------------------------ */
    public function destroy($skillId) {
        $skill = Skill::findOrFail($skillId);
        $this->authorize('delete', $skill);
        $this->skillService->delete($skill);

        return response()->json(["message" => "Skill deleted successfully"]);
    }
}
