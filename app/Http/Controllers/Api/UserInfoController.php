<?php

namespace App\Http\Controllers\Api;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Requests\UpdateProfileImageRequest;

class UserInfoController extends Controller {
    # Display a listing of the resource. 
    public function index() {
        //
    }

    #Store a newly created resource in storage. 
    public function store(StoreUserInfoRequest $request) {
        //
    }

    #Display the specified resource. 
    public function show() {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $info = $user->userInfo;
        return response()->json($info);
    }

    #Update the specified resource in storage. 
    public function update(UpdateUserInfoRequest $request) {
        $user = Auth::user();
        $validatedData = $request->validated();

        $userInfo = $user->userInfo;

        $userInfo->update([
            'firstName' => $validatedData['firstName'],
            'lastName' => $validatedData['lastName'],
            'headline' => $validatedData['headline'],
            'additionalName' => $validatedData['additionalName'],
            'pronouns' => $validatedData['pronouns'],
            'about' => $validatedData['about'],
            'location' => $validatedData['location'],
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }

    #Remove the specified resource from storage. 
    public function destroy(UserInfo $userInfo) {
        //
    }

    #update Profile photo
    public function updateProfile(UpdateProfileImageRequest $request) {
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $profilePath = Storage::disk('public')->put('user_profile_images', $request->file('profile_image'));
            $profilePath = 'storage/' . str_replace('\\', '/', $profilePath);

            #retrieve users profile image path from the database
            $oldProfileImage = $user->userInfo->profile_image;

            #delete the old profile image if its not default profile image
            if ($oldProfileImage !== "storage/user_profile_images/default-avatar.png") {
                $oldProfileImage = str_replace('storage/', '', $oldProfileImage);
                Storage::disk('public')->delete($oldProfileImage);
            }

            UserInfo::where('user_id', $user->id)->update([
                'profile_image' => $profilePath,
            ]);
        }

        return response()->json(['message' => 'Profile photo updated successfully']);
    }

    #update Cover photo
    public function updateCover(Request $request) {
        $user = Auth::user();

        $request->validate([
            'cover_image' => 'required|image',
        ]);

        if ($request->hasFile('cover_image')) {
            $profilePath = Storage::disk('public')->put('user_cover_images', $request->file('cover_image'));
            $profilePath = 'storage/' . str_replace('\\', '/', $profilePath);

            #retrieve users cover image path from the database
            $oldCoverImage = $user->userInfo->cover_image;

            #delete the old cover image if its not default cover image
            if ($oldCoverImage !== "storage/user_cover_images/default-cover.jpg") {
                $oldCoverImage = str_replace('storage/', '', $oldCoverImage);
                Storage::disk('public')->delete($oldCoverImage);
            }

            UserInfo::where('user_id', $user->id)->update([
                'cover_image' => $profilePath,
            ]);
        }

        return response()->json(['message' => 'Cover photo updated successfully']);
    }
}
