<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\AddPersonalRequest;
use App\Http\Requests\User\ChangeEmailConfirmationRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\RegisterPlayerRequest;
use App\Service\User\EmailConfirmationService;
use App\Service\User\UserPersonalDataService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

interface IUserController
{
    public function getMe(): JsonResponse;
    public function getPlayerList(): JsonResponse;
    public function registerPlayer(UserService $userService, RegisterPlayerRequest $request): JsonResponse;
    public function getPlayerByUuid(UserService $userService, string $uuid): JsonResponse;
    public function deletePlayer(UserService $userService, string $uuid): JsonResponse;
    public function confirmEmail(ChangeEmailConfirmationRequest $request, EmailConfirmationService $emailConfirmationService): JsonResponse;
    public function changeEmail(ChangeEmailRequest $request, UserService $userService): JsonResponse;
    public function changePassword(ChangePasswordRequest $request, UserService $userService): JsonResponse;
    public function addPersonalInfo(AddPersonalRequest $request, UserService $userService, UserPersonalDataService $personalDataService): JsonResponse;
}
