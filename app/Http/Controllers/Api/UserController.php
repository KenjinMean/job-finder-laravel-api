<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    public function store(Request $request) {

        return response(['message' => 'ok']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
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
}
