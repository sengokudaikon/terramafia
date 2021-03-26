<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Service\User\SocialAccountService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialAuthController extends Controller implements ISocialAuthController
{
    public function getAuthUrlForExternalService(
        Request $request,
        SocialAccountService $socialAccountService
    ): JsonResponse {
        $redirectUrl = $socialAccountService->getAuthUrl($request->get('provider'), $request->get('state'));

        return $this->successResponse(null, ['redirectUrl' => $redirectUrl]);
    }

    public function handleProviderCallback(string $provider, UserService $userService): JsonResponse
    {
        $token = $userService->authWithSocialProvider($provider);

        return $this->respondWithToken($token, __('users.auth.login.success'));
    }
}
