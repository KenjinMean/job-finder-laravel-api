<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Services\SocialAuthService;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller {
    private $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService) {
        $this->socialAuthService = $socialAuthService;
    }

    // |--------------------------------------------------------------------------
    public function getProviderAuthorizationUrl($provider) {
        try {
            $authorizationUrl = $this->socialAuthService->getProviderAuthorizationUrl($provider);
            return response()->json(['authorization_url' => $authorizationUrl]);
        } catch (\Exception $e) {
            return ResponseHelper::generateErrorResponse($e, 'Failed to get ' . $provider . ' authorization URL', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function redirectToProvider($provider) {
        try {
            return $this->socialAuthService->redirectToProvider($provider);
        } catch (Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "failed to redirect to . $provider . provider.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // |--------------------------------------------------------------------------
    public function handleProviderCallback($provider) {
        try {
            return $this->socialAuthService->handleProviderCallback($provider);
        } catch (\Exception $e) {
            return ResponseHelper::generateErrorResponse($e, 'Failed to authenticate with ' . $provider, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
