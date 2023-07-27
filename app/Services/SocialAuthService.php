<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class SocialAuthService {

    public function getProviderAuthorizationUrl($provider) {
        $redirectUrl = env('APP_URL') . '/api/auth/' . $provider . '/callback';
        $authorizationUrl = Socialite::driver($provider)->stateless()->redirectUrl($redirectUrl)->redirect()->getTargetUrl();
        return $authorizationUrl;
    }

    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider) {
        $socialiteUser = Socialite::driver($provider)->stateless()->user();
        $column = $provider . '_id';
        // $user = User::where($column, $socialiteUser->getId())->first();

        $user = User::where('email', $socialiteUser->getEmail())->first();

        if ($user) {
            if (!$user->google_id && !$user->github_id) {
                return ResponseHelper::errorResponse('This email already exists. Please log in using your email and password.', Response::HTTP_CONFLICT, "Account Conflict: Email Already taken");
            }
            if ($user->$column !== $socialiteUser->getId()) {
                return response()->json([
                    'message' => 'This email is already associated with a ' . ($provider === 'github' ? 'Google' : 'GitHub') . ' account. Would you like to log in with ' . ($provider === 'github' ? 'Google' : 'GitHub') . ' instead?',
                    'error' => 'Account Conflict: Email Already Linked',
                    'provider' => $provider === 'github' ? 'google' : 'github',
                ], Response::HTTP_CONFLICT);
            }
        }

        if (!$user) {
            $profileImage = $socialiteUser->getAvatar();

            $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
            $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

            $user = User::create([
                'email' => $socialiteUser->getEmail(),
                'password' => "password",
                'email_verified_at' => now(),
                $column => $socialiteUser->getId(),
            ]);

            UserInfo::create([
                'firstName' => $socialiteUser->getName(),
                'user_id' => $user->id,
                'profile_image' => $profileImage ?: $defaultProfileImagePath,
                'cover_image' => $defaultCoverImagePath,
            ]);
        }
        // Generate JWT token for the user
        $jwtToken = JWTAuth::fromUser($user);
        $user = User::with('userInfo')->find($user->id);
        if (!$user) {
            return response()->json(['error' => 'User not found with related UserInfo'], Response::HTTP_NOT_FOUND);
        }
        return redirect(env('FRONTEND_URL') . '/callback?token=' . $jwtToken);
    }
}
