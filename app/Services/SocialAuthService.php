<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use App\Helpers\JwtHelper;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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

            $defaultProfileImagePath = 'storage/user_profile_images/default-avatar.png';
            $defaultCoverImagePath = 'storage/user_cover_images/default-cover.jpg';

            $randomPassword = Str::random(12);

            $user = User::create([
                'email' => $socialiteUser->getEmail(),
                'password' => bcrypt($randomPassword),
                'email_verified_at' => now(),
                $column => $socialiteUser->getId(),
            ]);

            UserInfo::create([
                'first_name' => $socialiteUser->getName(),
                'last_name' => "",
                'user_id' => $user->id,
                'profile_image' => $profileImage ?: $defaultProfileImagePath,
                'cover_image' => $defaultCoverImagePath,
            ]);
        }

        $userData = JwtHelper::generateAccessToken($user);
        $response = json_encode($userData);
        $encodedResponse = urlencode($response);
        return redirect(env('FRONTEND_URL') . '/job-finder-react-frontend/auth/auth-provider-callback?response=' . $encodedResponse);
    }
}
