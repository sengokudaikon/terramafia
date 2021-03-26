<?php

namespace App\Service\User;

use App\Domain\User\Entity\PasswordResetToken;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\PasswordResetTokenRepository;
use App\Domain\User\Repository\UserRepository;
use App\Event\PasswordResetTokenCreatedEvent;
use App\Exceptions\UserNotFoundException;
use App\Helpers\DateTimeUtil;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class PasswordReminderService
{
    /**
     * @var int Время жизни токена восстановления пароля - 12 часов (в минутах).
     */
    public const TOKEN_LIFETIME = 720;

    /**
     * @var PasswordResetTokenRepository Репозиторий восстановления пароля.
     */
    private PasswordResetTokenRepository $passwordResetTokenRepository;

    /**
     * @var UserRepository Репозиторий пользователей.
     */
    private UserRepository $userRepository;

    /**
     * PasswordReminderService constructor.
     *
     * @param PasswordResetTokenRepository $passwordResetTokenRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        PasswordResetTokenRepository $passwordResetTokenRepository,
        UserRepository $userRepository
    ) {
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Напоминает пароль пользователю.
     *
     * @param string $email
     * @throws UserNotFoundException
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function remindForUser(string $email): void
    {
        $user = $this->userRepository->findUserByEmail($email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $passwordReset = $this->makeToken($user);

        event(new PasswordResetTokenCreatedEvent(
            $user->getUuid()->toString(),
            $passwordReset->getToken(),
            $passwordReset->getCreatedAt()
        ));
    }

    /**
     * Генерирует токен и сохраняет его.
     *
     * @param User $user
     * @return PasswordResetToken
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function makeToken(User $user): PasswordResetToken
    {
        $token = md5(Str::random());
        $passwordResetToken = new PasswordResetToken($user, $token);
        $this->passwordResetTokenRepository->add($passwordResetToken);

        return $passwordResetToken;
    }

    /**
     * Проверяет, истекло ли время жизни токена.
     *
     * @param PasswordResetToken $passwordResetToken
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isExpiredResetPassword(PasswordResetToken $passwordResetToken): bool
    {
        return (new DateTimeImmutable())
                ->diff(DateTimeUtil::addMinutes($passwordResetToken->getCreatedAt(), self::TOKEN_LIFETIME))
                ->invert !== 0;
    }

    /**
     * Возвращает пользователя по токену подтверждения.
     *
     * @param string $token
     *
     * @return User
     * @throws TokenExpiredException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserByToken(string $token): User
    {
        $passwordResetToken = $this->passwordResetTokenRepository->findByToken($token);

        if (is_null($passwordResetToken) || $this->isExpiredResetPassword($passwordResetToken)) {
            throw new TokenExpiredException();
        }

        return $passwordResetToken->getUser();
    }

    /**
     * Удаляет токены пользователя.
     *
     * @param User $user
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTokensByUser(User $user): void
    {
        $this->passwordResetTokenRepository->deleteByUser($user);
    }

    /**
     * Ищет токен по пользователю.
     *
     * @param User $user
     * @return PasswordResetToken|null
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUser(User $user): ?PasswordResetToken
    {
        return $this->passwordResetTokenRepository->findByUser($user);
    }
}
