<?php

namespace App\Service\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserPersonal;
use App\Domain\User\Entity\VO\Role;
use App\Domain\User\Repository\UserRepository;
use App\Event\UserCreatedEvent;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserNotFoundException;
use App\Service\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\SecurityHashHelper;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * @var UserRepository Репозиторий пользователя.
     */
    private UserRepository $userRepository;

    /**
     * @var PasswordReminderService Сервис напоминания пароля.
     */
    private PasswordReminderService $passwordReminderService;

    /**
     * @var SocialAccountService Сервис аккаунтов соц. сетей.
     */
    private SocialAccountService $socialAccountService;

    /**
     * @var EmailConfirmationService Сервис подтвержденных email.
     */
    private EmailConfirmationService $emailConfirmationService;

    /**
     * Конструктор сервиса пользователя.
     *
     * @param UserRepository          $userRepository
     * @param PasswordReminderService  $passwordReminderService
     * @param SocialAccountService     $socialAccountService
     * @param EmailConfirmationService $emailConfirmationService
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordReminderService $passwordReminderService,
        SocialAccountService $socialAccountService,
        EmailConfirmationService $emailConfirmationService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordReminderService = $passwordReminderService;
        $this->socialAccountService = $socialAccountService;
        $this->emailConfirmationService = $emailConfirmationService;
    }

    /**
     * Регистрация пользователя.
     *
     * @param string      $email
     * @param string      $password
     * @param string|null $playerName
     *
     * @return User
     * @throws UserNotFoundException
     */
    public function addPlayer(string $email, string $password, ?string $playerName): User
    {
        $user = $this->findByEmail($email);
        $hashedPassword = self::hashPassword($password);

        if ($user) {
            $user->setPassword($hashedPassword);
            $this->userRepository->update($user);
        }  elseif (!$user->getActivity()){
            $user->activate();
        } else {
            $user = new User(
                $playerName,
                $email,
                $hashedPassword,
                new Role(Role::PLAYER)
            );

            $user->activate();

            $this->userRepository->add($user);
        }

        event(new UserCreatedEvent($user->getId()));

        return $user;
    }

    /**
     * Обновление никнейма игрока.
     *
     * @param User   $user
     * @param string $playerName
     *
     * @return User
     */
    public function updatePlayer(User $user, string $playerName): User
    {
        $user->setPlayerName($playerName);
        $this->userRepository->update($user);

        return $user;
    }

    /**
     * Поиск пользователя по email.
     *
     * @param string $email
     *
     * @return User|null
     *
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User $user */

        return $this->userRepository->findUserByEmail($email);
    }

    /**
     * Хеширует пароль.
     *
     * @param string $value
     * @return string
     */
    public static function hashPassword(string $value): string
    {
        return SecurityHashHelper::generatePasswordHash($value);
    }

    /**
     * Аутентификация пользователя.
     *
     * @param string $email
     * @param string $password
     * @param bool $rememberMe
     * @return string
     */
    public function auth(
        string $email,
        string $password,
        bool $rememberMe = false
    ): string {
        $ttlMinutes = $rememberMe ? config('jwt.remember_me_ttl') : config('jwt.ttl');
        JWTAuth::factory()->setTTL($ttlMinutes);

        $token = JWTAuth::attempt(
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        if (!$token) {
            throw new InvalidCredentialsException;
        }

        return $token;
    }

    /**
     * Разлогинить текущего авторизованного пользователя.
     */
    public function logout(): void
    {
        JWTAuth::setToken(JWTAuth::getToken())->invalidate();
    }

    /**
     * Возвращает пользователя по идентификатору.
     *
     * @param string $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getById(string $userId): User
    {
        return $this->userRepository->findUserByUuid($userId);
    }

    /**
     * Удаляет пользователя по идентификатору.
     *
     * @param string $uuid
     *
     * @throws UserNotFoundException
     */
    public function delete(string $uuid): void
    {
        $user = $this->getById($uuid);

        $this->userRepository->remove($user);
    }

    /**
     * Изменить пароль пользователя.
     *
     * @param string $token
     * @param string $newPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \InvalidArgumentException
     * @throws \Tymon\JWTAuth\Exceptions\TokenExpiredException
     */
    public function changePasswordWithResetToken(string $token, string $newPassword): void
    {
        $user = $this->passwordReminderService->getUserByToken($token);
        $user->setPassword(self::hashPassword($newPassword));
        $this->userRepository->update($user);

        $this->passwordReminderService->deleteTokensByUser($user);
    }

    /**
     * Аутентификация через провайдер социального аккаунта.
     *
     * @param string $provider
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function authWithSocialProvider(string $provider): string
    {
        $user = $this->socialAccountService->getUserByProvider($provider);

        return $this->setAuthUser($user);
    }

    /**
     * Назанчить пользователя авторизованным.
     *
     * @param User $user
     * @return string
     */
    public function setAuthUser(User $user): string
    {
        Auth::login($user, true);

        return JWTAuth::fromUser($user);
    }

    /**
     * Изменяет пароль пользователя.
     *
     * @param string $userId
     * @param string $currentPassword
     * @param string $newPassword
     *
     * @throws InvalidPasswordException
     * @throws \Doctrine\ORM\ORMException
     */
    public function changePasswordFromAccount(string $userId, string $currentPassword, string $newPassword): void
    {
        $user = $this->userRepository->findUserByUuid($userId);

        if (!Hash::check($currentPassword, $user->getPassword())) {
            throw new InvalidPasswordException;
        }

        $user->setPassword(self::hashPassword($newPassword));
        $this->userRepository->update($user);
    }

    /**
     * Обработка запроса на изменение email пользователя.
     *
     * @param string $userId
     * @param string $currentPassword
     * @param string $newEmail
     *
     * @throws EmailAlreadyExistsException
     * @throws InvalidPasswordException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function changeEmailFromAccount(string $userId, string $currentPassword, string $newEmail)
    {
        $user = $this->userRepository->findUserByUuid($userId);

        if (!Hash::check($currentPassword, $user->getPassword())) {
            throw new InvalidPasswordException;
        }

        if (!$this->canEmailRegister($newEmail)) {
            throw new EmailAlreadyExistsException($newEmail);
        }

        $token = $this->emailConfirmationService->makeToken($user, $newEmail);

        //TODO: добавить отправку email
    }

    /**
     * Проверяет, может ли данный email быть зарегистрированным.
     *
     * @param string $email
     *
     * @return bool
     * @throws UserNotFoundException
     */
    public function canEmailRegister(string $email): bool
    {
        $user = $this->userRepository->findUserByEmail($email);

        if (!$user) {
            return true;
        }

        if ($user->getActivity()->isConfirmed() || $user->getActivity()->isEmailVerified()) {
            return false;
        }

        return true;
    }

    /**
     * Добавляет персональные данные пользователю.
     *
     * @param User         $user
     * @param UserPersonal $personalData
     */
    public function addPersonalData(User $user, UserPersonal $personalData): void
    {
        $user->addPersonal($personalData);
        $this->userRepository->update($user);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAllUsers();
    }
}
