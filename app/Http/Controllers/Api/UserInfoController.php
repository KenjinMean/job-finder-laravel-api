<?php

namespace App\Http\Controllers\Api;

use App\Models\UserInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;

class UserInfoController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserInfoRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInfo $userInfo) {
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
    public function destroy(UserInfo $userInfo) {
        //
    }

    public function getInfo() {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $info = $user->userInfo;
        return response()->json($info);
    }
}
