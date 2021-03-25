<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\PasswordResetToken;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

class PasswordResetTokenRepository extends BaseRepository
{
    /**
     * Добавление нового токена восстановления.
     *
     * @param PasswordResetToken $passwordResetToken
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function add(PasswordResetToken $passwordResetToken): void
    {
        $this->entityManager->persist($passwordResetToken);
        $this->entityManager->flush($passwordResetToken);
    }

    /**
     * Ищет токен в базе данных.
     *
     * @param string $token
     *
     * @return PasswordResetToken
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function findByToken(string $token): PasswordResetToken
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();
        $query = $queryBuilder
            ->select('pass')
            ->from(PasswordResetToken::class, 'pass')
            ->where('pass.token = :value')
            ->getQuery();

        $query->setParameter('value', $token);

        /** @var PasswordResetToken|null $passToken */
        $passToken = $query->getOneOrNullResult();

        if (!$passToken) {
            throw new EntityNotFoundException("Токен $token сброса пароля не найден");
        }

        return $passToken;
    }

    /**
     * Удаление токена пользователя.
     *
     * @param User $user
     */
    public function deleteByUser(User $user): void
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();
        $query = $queryBuilder
            ->select('pass')
            ->from(PasswordResetToken::class, 'pass')
            ->where('pass.user = :user')
            ->getQuery();

        $query->setParameter('user', $user);
        $tokens = $query->getResult();

        foreach ($tokens as $passwordResetToken) {
            $this->remove($passwordResetToken);
        }
    }

    private function remove(PasswordResetToken $passwordResetToken): void
    {
        $this->entityManager->remove($passwordResetToken);
        $this->entityManager->flush($passwordResetToken);
    }

    /**
     * Поиск токена по пользователю.
     *
     * @param User $user
     *
     * @return PasswordResetToken
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function findByUser(User $user): PasswordResetToken
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();
        $query = $queryBuilder
            ->select('pass')
            ->from(PasswordResetToken::class, 'pass')
            ->where('pass.user = :user')
            ->getQuery();

        $query->setParameter('user', $user);

        /** @var PasswordResetToken $token */
        $token = $query->getOneOrNullResult();

        if (!$token) {
            throw new EntityNotFoundException("Токен сброса пароля для данного пользователя не найден");
        }

        return $token;
    }
}
