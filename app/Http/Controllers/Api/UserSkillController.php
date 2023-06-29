<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserSkillController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show() {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update() {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy() {
        //
    }

    public function setSkill(Request $request) {
        $user = Auth::user();
        /** @var User $user */
        $user->skills()->sync($request->input('skills'));
        return response()->json([
            "message" => "Skills updated successfully."
        ]);
    }
}
