<?php

namespace App\Service\User;

use App\Domain\User\Entity\EmailConfirmationToken;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\IEmailConfirmationRepository;
use App\Domain\User\Repository\IUserRepository;
use App\Helpers\DateTimeUtil;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class EmailConfirmationService
{
    /**
     * @var int Время жизни токена подтверждения - 12 часов (в минутах).
     */
    const TOKEN_LIFETIME = 720;

    /**
     * @var IUserRepository Репозиторий пользователей.
     */
    private IUserRepository $userRepository;

    /**
     * @var IEmailConfirmationRepository Репозиторий токенов подтверждения email пользователей.
     */
    private IEmailConfirmationRepository $emailConfirmationRepository;

    /**
     * @var EntityManagerInterface Менеджер сущностей.
     */
    private EntityManagerInterface $entityManager;

    /**
     * Конструктор сервиса подтвержденных email пользователей.
     *
     * @param IUserRepository $userRepository
     * @param IEmailConfirmationRepository $emailConfirmationRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        IUserRepository $userRepository,
        IEmailConfirmationRepository $emailConfirmationRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->emailConfirmationRepository = $emailConfirmationRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Генерирует токен подтверждения email.
     *
     * @param User   $user
     * @param string $email
     *
     * @return string
     */
    public function makeToken(User $user, string $email): string
    {
        $token = md5(Str::random());
        $emailConfirmationToken = new EmailConfirmationToken($user, $token, $email);
        $this->emailConfirmationRepository->add($emailConfirmationToken);

        return $token;
    }

    /**
     * Подтверждает email адрес пользователя по токену.
     *
     * @param string $token
     *
     * @throws TokenExpiredException
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function confirmEmailByToken(string $token): void
    {
        $emailConfirmationToken = $this->getToken($token);
        $user = $emailConfirmationToken->getUser();

        if ($user->getActivity()){
            $user->setEmail($emailConfirmationToken->getEmail())
                ->getActivity()
                ->verifyEmail();
            $user->setEmail($emailConfirmationToken->getEmail())
                ->getActivity()
                ->confirm();
        }

        $this->userRepository->update($user);

        $this->deleteTokensByUser($user);
    }

    /**
     * Возвращает токен подтверждения.
     *
     * @param string $token
     *
     * @return EmailConfirmationToken
     * @throws TokenExpiredException
     * @throws InvalidArgumentException
     */
    private function getToken(string $token): EmailConfirmationToken
    {
        $emailConfirmationToken = $this->emailConfirmationRepository->findByToken($token);

        if (is_null($emailConfirmationToken) || $this->isExpiredToken($emailConfirmationToken)) {
            throw new TokenExpiredException;
        }

        return $emailConfirmationToken;
    }

    /**
     * Проверяет, истокло ли время жизни.
     *
     * @param EmailConfirmationToken $emailConfirmationToken
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    private function isExpiredToken(EmailConfirmationToken $emailConfirmationToken): bool
    {
        return (new DateTimeImmutable)
                ->diff(DateTimeUtil::addMinutes($emailConfirmationToken->getCreatedAt(), self::TOKEN_LIFETIME))
                ->invert !== 0;
    }

    /**
     * Удаляет токены подтверждения email пользователя.
     *
     * @param User $user
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function deleteTokensByUser(User $user): void
    {
        $this->emailConfirmationRepository->deleteByUser($user);
        $this->entityManager->flush();
    }

    /**
     * Возвращает пользователя по токену подтверждения.
     *
     * @param string $token
     *
     * @return User
     * @throws TokenExpiredException
     * @throws InvalidArgumentException
     */
    public function getUserByToken(string $token): User
    {
        return $this->getToken($token)->getUser();
    }
}