<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\EmailConfirmationToken;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

class EmailConfirmationRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Добавление нового токена подтверждения.
     *
     * @param EmailConfirmationToken $emailConfirmationToken
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function add(EmailConfirmationToken $emailConfirmationToken): void
    {
        $this->getEntityManager()->persist($emailConfirmationToken);
        $this->getEntityManager()->flush($emailConfirmationToken);
    }

    /**
     * Ищет токен.
     *
     * @param string $token
     *
     * @return EmailConfirmationToken
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function findByToken(string $token): EmailConfirmationToken
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();
        $query = $queryBuilder
            ->select('email')
            ->from(EmailConfirmationToken::class, 'email')
            ->where('email.token = :value')
            ->getQuery();

        $query->setParameter('value', $token);

        /** @var EmailConfirmationToken $emailToken */
        $emailToken = $query->getOneOrNullResult();

        if (!$emailToken) {
            throw new EntityNotFoundException("Токен $token подтверждения почты не найден");
        }

        return $emailToken;
    }

    /**
     * Удаляет токены пользователя.
     *
     * @param User $user
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function deleteByUser(User $user): void
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();
        $query = $queryBuilder
            ->select('email')
            ->from(EmailConfirmationToken::class, 'email')
            ->where('email.user = :user')
            ->getQuery();

        $query->setParameter('user', $user);
        $tokens = $query->getResult();

        if ($tokens) {
            foreach ($tokens as $emailConfirmationToken) {
                $this->remove($emailConfirmationToken);
            }
        } else {
            throw new EntityNotFoundException("Пользователь {$user->getExternalisedUuid()} не имеет токенов подтверждения почты");
        }

        $this->getEntityManager()->flush();
    }

    /**
     * Удаляет токен.
     *
     * @param EmailConfirmationToken $emailConfirmationToken
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function remove(EmailConfirmationToken $emailConfirmationToken): void
    {
        $this->getEntityManager()->remove($emailConfirmationToken);
        $this->getEntityManager()->flush($emailConfirmationToken);
    }
}
