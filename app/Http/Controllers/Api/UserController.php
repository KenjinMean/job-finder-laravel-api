<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return response(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request) {
        $validatedData = $request->validated();
        $user = User::create($validatedData);
        return response()->json(['message' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show() {
        return response(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    public function updateSkill(Request $request) {
        $user = Auth::user();
        /** @var User $user */
        $user->skills()->sync($request->input('skills'));
        return response()->json([
            "message" => "Skills updated successfully."
        ]);
    }
}
