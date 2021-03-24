<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\AuthenticateUserRequest;
use App\Http\Requests\User\ExternalAuthRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Service\User\PasswordReminderService;
use App\Service\User\SocialAccountService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

interface IUserAuthController
{
    public function authenticate(UserService $userService, AuthenticateUserRequest $request): JsonResponse;
    public function logout(UserService $userService): JsonResponse;
    public function forgot(ForgotPasswordRequest $request, PasswordReminderService $passwordReminderService): JsonResponse;
    public function resetPassword(ResetPasswordRequest $request, UserService $userService): JsonResponse;
    public function getAuthUrlForExternalService(ExternalAuthRequest $request, SocialAccountService $socialAccountService): JsonResponse;
    public function handleProviderCallback(string $provider, UserService $userService): JsonResponse;
}
