<?php

namespace App\Http\Controllers\User;

use App\Domain\User\Entity\User;
use App\Helpers\Response;
use App\Http\Controllers\Controller as AbstractController;
use App\Http\Requests\User\AuthenticateUserRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Service\Auth;
use App\Service\User\PasswordReminderService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class UserAuthController extends AbstractController implements IUserAuthController
{
    public function login(UserService $userService, AuthenticateUserRequest $request): JsonResponse
    {
        $token = $userService->auth(
            $request->input('email'),
            $request->input('password'),
            $request->boolean('rememberMe')
        );

        /** @var User $user */
        $user = Auth::user();

        if ($user && !$user->getActivity()->isEmailVerified()) {
            return $this->errorResponse(
                __('users.auth.login.emailNotVerified'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respondWithToken($token, __('users.auth.login.success'));
    }

    public function logout(UserService $userService): JsonResponse
    {
        $userService->logout();

        return $this->successResponse(__('users.auth.logout.success'))
            ->withCookie(Cookie::forget('token'));
    }

    public function forgot(ForgotPasswordRequest $request, PasswordReminderService $passwordReminderService): JsonResponse
    {
        $passwordReminderService->remindForUser($request->input('email'));

        return $this->successResponse(
            sprintf(
                __('users.auth.password.forgot.success'),
                $request->input('email')
            ));
    }

    public function resetPassword(ResetPasswordRequest $request, UserService $userService): JsonResponse
    {
        $userService->changePasswordWithResetToken(
            $request->input('token'),
            $request->input('password')
        );

        return $this->successResponse(__('users.auth.password.reset.success'));
    }
}
