<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserPartialResource;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $user = User::with('company')->get();
        return UserPartialResource::collection($user);
        // return UserPartialResource::collection(User::with('company')->get());
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
    public function show(string $id) {
        $user = User::with('company')->findOrFail($id);
        // return UserPartialResource::collection($user);
        return response()->json(['message' => 'Ok', 'user' => $user]);
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

    public function getCompany() {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $company = $user->company;
        return response()->json($company);
    }
}
