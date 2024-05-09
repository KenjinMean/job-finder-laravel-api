<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use App\Helpers\JwtHelper;
use App\Models\UserContact;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService {

    public function getProviderAuthorizationUrl($provider) {
        $redirectUrl = env('APP_URL') . '/api/auth/' . $provider . '/callback';
        $authorizationUrl = Socialite::driver($provider)->stateless()->redirectUrl($redirectUrl)->redirect()->getTargetUrl();
        return $authorizationUrl;
    }

    // |--------------------------------------------------------------------------
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    // |--------------------------------------------------------------------------
    public function handleProviderCallback($provider) {

        $error = request('error');

        if ($error === 'access_denied') {
            return redirect(env('FRONTEND_URL') . '/auth/login?error=' . urlencode('Access denied. Please try again.'));
        }

        $socialiteUser = Socialite::driver($provider)->stateless()->user();
        $column = $provider . '_id';
        $user = User::where($column, $socialiteUser->getId())->first();

        $existingUser = User::where('email', $socialiteUser->getEmail())->first();

        if ($existingUser) {
            if (!$existingUser->google_id && !$existingUser->github_id) {
                return redirect(env('FRONTEND_URL') . '/auth/login?error=' . urlencode('This email already exists'));
            }
            if ($existingUser->$column != $socialiteUser->getId()) {
                $providerName = $provider === 'github' ? 'Google' : 'GitHub';
                $errorMessage = "Email already associated with " . $providerName;
                return redirect(env('FRONTEND_URL') . '/auth/login?error=' . urlencode($errorMessage));
            }
        }

        if (!$user) {
            $profileImage = $socialiteUser->getAvatar();

            $randomPassword = Str::random(12);

            $user = User::create([
                'email' => $socialiteUser->getEmail(),
                'password' => bcrypt($randomPassword),
                'email_verified_at' => now(),
                $column => $socialiteUser->getId(),
            ]);

            UserInfo::create([
                'user_id' => $user->id,
                'first_name' => $socialiteUser->getName(),
                'last_name' => "",
                'profile_image' => $profileImage ?: null,
            ]);

            UserContact::create([
                'user_id' => $user->id,
            ]);
        }

        // FIX: how to pass the authenticatedUserData directly in the redirect
        $AuthenticatedUserData = JwtHelper::generateAccessToken($user);
        $UserResponse =  response()->json($AuthenticatedUserData);
        $response = json_encode($UserResponse);
        $encodedResponse = urlencode($response);
        return redirect(env('FRONTEND_URL') . '/auth/auth-provider-callback?response=' . $encodedResponse);
    }
}
