<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\SocialAuthService;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller {
    private $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService) {
        $this->socialAuthService = $socialAuthService;
    }
    # Socialite methods
    public function redirectToProvider($provider) {
        return $this->socialAuthService->redirectToProvider($provider);
    }

    public function handleProviderCallback($provider) {
        try {
            $user = $this->socialAuthService->handleProviderCallback($provider);
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with ' . $provider]);
        }
    }
}
