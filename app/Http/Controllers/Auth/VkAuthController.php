<?php

namespace App\Http\Controllers\Auth;

use App\Builders\UrlBuilder;
use App\Enums\OAuthLoginResult;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Service\Auth;
use App\Service\User\Social\VkontakteSocialAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class VkAuthController extends Controller
{
    protected UserRepository $userRepository;
    protected UrlBuilder $urlBuilder;
    protected string $token;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepository
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(UserRepository $userRepository, UrlBuilder $urlBuilder)
    {
        $this->userRepository = $userRepository;
        $this->urlBuilder = $urlBuilder;
        $this->token = '';
    }

    public function redirect(Request $request)
    {
        $redirect = $request->header('referer');
        Session::put('oauth_redirect', $redirect);

        return Socialite::driver('vkontakte');
    }

    public function handleCallback(Request $request)
    {
        try {
            /** @var VkontakteSocialAccount $oAuthUser */
            $oAuthUser = Socialite::driver('vkontakte')->user();
        } catch (Throwable $exception) {
            if (! $exception instanceof ClientException || $exception->getResponse()->getStatusCode() !== 401) {
                app()->make('sentry')->captureException($exception);
            }

            return $this->resolveRedirect($request, OAuthLoginResult::UnableToRetrieveUserDetails);
        }

        try {
            if (! $this->userRepository->existsWithVkUserId((int) $oAuthUser->getId())) {
                return $this->register($oAuthUser, $request);
            }

            return $this->login($oAuthUser, $request);
        } catch (Throwable $exception) {
            app()->make('sentry')->captureException($exception);

            return $this->resolveRedirect($request, OAuthLoginResult::UnableToCreateUser);
        }
    }

    protected function register(
        VkontakteSocialAccount $oAuthUser,
        Request $request
    ): RedirectResponse {
        $user = $this->userRepository->createFromOAuthProfile($oAuthUser);

        \Illuminate\Support\Facades\Auth::login($user, true);
        $this->token = $user->createToken('auth')->plainTextToken;
        return $this->resolveRedirect($request, OAuthLoginResult::CreatedSuccessfully);
    }

    protected function login(
        VkontakteSocialAccount $oAuthUser,
        Request $request
    ) {
        /** @var User $user */
        $user = $this->userRepository->findByVkUserId((int) $oAuthUser->getId());

        Auth::login($user, true);
        $this->token = $user->createToken('auth')->plainTextToken;
        return $this->resolveRedirect($request, OAuthLoginResult::LoggedInSuccessfully);
    }

    protected function resolveRedirect(Request $request, string $result): \Illuminate\Http\RedirectResponse
    {
        $queryParams = [
            'result' => $result,
            'token' => $this->token
        ];

        return Redirect::away($this->urlBuilder->getOAuthRedirectUrl($queryParams));
    }
}
