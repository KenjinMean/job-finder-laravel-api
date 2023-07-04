<?php

namespace App\Http\Controllers\Api;

use App\Models\Skill;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;

class SkillController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $skills = Skill::all();
        return SkillResource::collection($skills);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSkillRequest $request) {
        $validatedSkill = $request->validated();
        Skill::create($validatedSkill);
        return response()->json(['message' => "skill created successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill) {
        //
    }
}
