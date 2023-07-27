<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Helpers\ExceptionHelper;
use App\Services\SocialAuthService;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller {
    private $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService) {
        $this->socialAuthService = $socialAuthService;
    }

    # Socialite methods
    public function getProviderAuthorizationUrl($provider) {
        try {
            $authorizationUrl = $this->socialAuthService->getProviderAuthorizationUrl($provider);
            return response()->json(['authorization_url' => $authorizationUrl]);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('Failed to get ' . $provider . ' authorization URL', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function redirectToProvider($provider) {
        try {
            return $this->socialAuthService->redirectToProvider($provider);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function handleProviderCallback($provider) {
        try {
            // return redirect('http://localhost:3000/callback?token=' . $token);
            // return redirect(env('FRONTEND_URL') . '/callback?token=' . $token);
            $redirectUrl = $this->socialAuthService->handleProviderCallback($provider);
            return $redirectUrl;
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('Failed to authenticate with ' . $provider, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
