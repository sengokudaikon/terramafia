<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserSocialAccount;
use App\Exceptions\UserNotFoundException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

class UserSocialAccountRepository extends BaseRepository
{
    /**
     * Добавляет новый соц. аккаунт пользователя.
     *
     * @param UserSocialAccount $socialAccount
     *
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     */
    public function add(UserSocialAccount $socialAccount): void
    {
        $this->getEntityManager()->persist($socialAccount);
        $this->getEntityManager()->flush($socialAccount);
    }

    public function update(UserSocialAccount $socialAccount): void
    {
        $this->getEntityManager()->persist($socialAccount);
        $this->getEntityManager()->flush($socialAccount);
    }

    public function remove(UserSocialAccount $socialAccount): void
    {
        $this->getEntityManager()->remove($socialAccount);
        $this->getEntityManager()->flush($socialAccount);
    }

    /**
     * Ищет соц. аккаунт пользователя по провайдеру и внешнему идентификатору.
     *
     * @param string $providerName
     * @param string $providerId
     *
     * @return UserSocialAccount|null
     * @throws UserNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAccountByProvider(string $providerName, string $providerId): ?UserSocialAccount
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $query = $queryBuilder
            ->select('user')
            ->from(UserSocialAccount::class, 'user')
            ->where('provider.name = :providerName')
            ->andWhere('provider.accountId = :providerId')
            ->setParameter('providerName', $providerName)
            ->setParameter('providerId', $providerId)
            ->getQuery();

        $socialAccount = $query->getOneOrNullResult();

        if (!$socialAccount) {
            throw new UserNotFoundException(null, "User with that social account does not exist");
        }

        return $socialAccount;
    }
}
