<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Domain\User\Entity\User;
use App\Helpers\DI;
use App\Http\Controllers\Controller as AbstractController;
use App\Http\Requests\User\AddPersonalRequest;
use App\Http\Requests\User\ChangeEmailConfirmationRequest;
use App\Http\Requests\User\SendEmailRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\RegisterPlayerRequest;
use App\Http\Requests\User\UpdatePlayerRequest;
use App\Service\Auth;
use App\Service\User\EmailConfirmationService;
use App\Service\User\UserPersonalDataService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends AbstractController implements IUserController, IUserAccountController
{
    public function registerPlayer(UserService $userService, RegisterPlayerRequest $request): JsonResponse
    {
        $user = $userService->addPlayer(
            $request->input('email'),
            $request->input('password'),
            $request->input('playerName')
        );

        return $this->successResponseCreated($user->getExternalisedUuid());
    }

    public function getMe(UserService $userService): JsonResponse
    {
        $user = $userService->getById(Auth::uuid());
        return $this->dataResponse($user->toJson());
    }

    public function getPlayerList(UserService $userService): JsonResponse
    {
        $users = $userService->getAll();
        return $this->dataResponse($users);
    }

    public function updatePlayer(UserService $userService, UpdatePlayerRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $userService->updatePlayer($user, $request->input('playerName'));

        return $this->dataResponse($user->toJson());
    }

    public function getPlayerByUuid(string $uuid, UserService $userService): JsonResponse
    {
        $user = $userService->getById($uuid);

        return $this->dataResponse($user->toJson());
    }

    public function deletePlayer(string $uuid, UserService $userService): JsonResponse
    {
        $userService->delete($uuid);

        return $this->successResponseWithoutContent();
    }

    public function confirmEmail(
        ChangeEmailConfirmationRequest $request,
        EmailConfirmationService $emailConfirmationService
    ): JsonResponse {
        $user = $emailConfirmationService->getUserByToken($request->input('confirmationToken'));
        $emailConfirmationService->confirmEmailByToken($request->input('confirmationToken'));
        $token = DI::getUserService()->setAuthUser($user);

        return $this->respondWithToken(
            $token,
            __('users.account.email.confirm.success')
        );
    }

    public function sendEmailVerification(SendEmailRequest $request, UserService $userService): JsonResponse
    {
        $token = $userService->sendEmailVerification(
            $request->input('currentPassword'),
            $request->input('email')
        );

        return $this->dataResponse(['confirmationToken' => $token]);
    }

    public function changePassword(ChangePasswordRequest $request, UserService $userService): JsonResponse
    {
        $userService->changePasswordFromAccount(
            Auth::uuid(),
            $request->input('currentPassword'),
            $request->input('newPassword')
        );

        return $this->successResponseWithoutContent();
    }

    public function addPersonalInfo(AddPersonalRequest $request, UserService $userService, UserPersonalDataService $personalDataService): JsonResponse
    {
        $user = $userService->getById(Auth::uuid());
        $personalDataService->addPersonalData(
            $user,
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('patronymic'),
            $request->get('birthdate'),
            $request->get('gender')
        );

        return $this->successResponseWithoutContent();
    }
}
