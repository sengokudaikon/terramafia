<?php

namespace App\Service\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserSocialAccount;
use App\Domain\User\Entity\VO\Role;
use App\Domain\User\Entity\VO\SocialProvider;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserSocialAccountRepository;
use App\Helpers\AppHelper;
use App\Service\User\Social\SocialAccount;
use App\Service\User\Social\SocialAccountManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use RuntimeException;

class SocialAccountService
{
    /**
     * @var array Карта запрашиваемых прав для приложений.
     */
    public const PROVIDER_SCOPE_MAP = [
        SocialProvider::VKONTAKTE => [
            'email'
        ],
        SocialProvider::GOOGLE => [
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email'
        ],
        SocialProvider::FACEBOOK => [
            'email',
            'user_gender',
            'user_birthday'
        ],
    ];

    /**
     * @var UserSocialAccountRepository Репозиторий соц. аккаунтов.
     */
    private UserSocialAccountRepository $userSocialAccountRepository;

    /**
     * @var UserRepository Репозиторий пользователей.
     */
    private UserRepository $userRepository;

    /**
     * @var PasswordReminderService Сервис востановления паролей.
     */
    private PasswordReminderService $passwordService;

    /**
     * Конструктор сервиса соц. аккаунтов.
     *
     * @param UserSocialAccountRepository $userSocialAccountRepository
     * @param UserRepository              $userRepository
     * @param PasswordReminderService      $passwordService
     */
    public function __construct(
        UserSocialAccountRepository $userSocialAccountRepository,
        UserRepository $userRepository,
        PasswordReminderService $passwordService,
    ) {
        $this->userSocialAccountRepository = $userSocialAccountRepository;
        $this->passwordService = $passwordService;
        $this->userRepository = $userRepository;
    }


    /**
     * Возвращает URL для аутентификации через социальный аккаунт.
     *
     * @param string $provider
     * @param string $state
     * @return string
     */
    public function getAuthUrl(string $provider, string $state): string
    {
        $scope = implode(' ', self::PROVIDER_SCOPE_MAP[$provider]);
        $params = [
            'scope' => $scope,
            'redirect_uri' => self::getRedirectUri($provider),
            'state' => $state
        ];

        return Socialite::driver($provider)
            ->with($params)
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Возвращает ссылку для авторизации у социального провайдера.
     *
     * @param string $provider
     * @param bool $withBind
     * @return string
     */
    private static function getRedirectUri(string $provider, bool $withBind = false): string
    {
        $url = config(sprintf($withBind ? 'services.%s.bind_redirect' : 'services.%s.redirect', $provider));

        // Чтобы авторизация через соц. аккаунты корректно работала на localhost,
        // для dev окружения заменяем хост в ссылке для переадресации на хост ссылающегося реферала.
        if (AppHelper::isDevEnvironment() && !empty(request()->headers->get('devhost'))) {
            $url = Str::replaceFirst(config('app.frontend_url'), request()->headers->get('devhost'), $url);
        }

        return $url;
    }

    /**
     * Привязывает социальный аккаунт к пользователю или создает нового.
     *
     * @param string $provider
     * @return User
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getUserByProvider(string $provider): User
    {
        $account = (new SocialAccountManager($provider))->getAccount();
        $userSocialAccount = $this->userSocialAccountRepository->findAccountByProvider(
            $provider,
            $account->getId()
        );

        if ($userSocialAccount) {
            return $userSocialAccount->getUser();
        }

        $user = $account->getEmail() ? $this->userRepository->findUserByEmail($account->getEmail()) : null;

        if (!$user) {
            $user = $this->makeUser($account);
            $this->createSocialAccountForUser($user, $account, true);
        } else {
            $this->createSocialAccountForUser($user, $account);
        }

        return $user;
    }

    /**
     * Создает сущность пользователя по данным внешнего социального аккаунта пользователя.
     *
     * @param SocialAccount $socialAccount
     * @return User
     * @throws ORMException
     */
    private function makeUser(SocialAccount $socialAccount): User
    {
        $user = new User(
            $socialAccount->getName(),
            $socialAccount->getEmail(),
            Str::random(20),
            new Role(Role::PLAYER)
        );

        $user->activate();
        $user->getActivity()->confirm();

        if ($socialAccount->getEmail()) {
            $user->getActivity()->verifyEmail();
        }

        $this->userRepository->add($user);
        //TODO напомнить пользователю сбросить пароль
        $this->passwordService->remindForUser($socialAccount->getEmail());
        //TODO напомнить пользователю ввести игровой ник
        return $user;
    }

    /**
     * Создает социальный аккаунт для пользователя.
     *
     * @param User $user
     * @param SocialAccount $socialAccount
     * @param bool $isNewUser
     * @return UserSocialAccount
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws InvalidArgumentException
     */
    private function createSocialAccountForUser(
        User $user,
        SocialAccount $socialAccount,
        bool $isNewUser = false
    ): UserSocialAccount {
        $userSocialAccount = new UserSocialAccount($user, $socialAccount->getProvider(), $socialAccount->getId());
        $userSocialAccount->setCredential($isNewUser);
        $this->userSocialAccountRepository->add($userSocialAccount);

        return $userSocialAccount;
    }
}
