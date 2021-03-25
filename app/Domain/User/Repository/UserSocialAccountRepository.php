<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserSocialAccount;
use App\Exceptions\UserNotFoundException;

class UserSocialAccountRepository extends BaseRepository
{
    /**
     * Добавляет новый соц. аккаунт пользователя.
     *
     * @param UserSocialAccount $socialAccount
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function add(UserSocialAccount $socialAccount): void
    {
        $this->entityManager->persist($socialAccount);
        $this->entityManager->flush($socialAccount);
    }

    public function update(UserSocialAccount $socialAccount): void
    {
        $this->entityManager->persist($socialAccount);
        $this->entityManager->flush($socialAccount);
    }

    public function remove(UserSocialAccount $socialAccount): void
    {
        $this->entityManager->remove($socialAccount);
        $this->entityManager->flush($socialAccount);
    }

    /**
     * Ищет соц. аккаунт пользователя по провайдеру и внешнему идентификатору.
     *
     * @param string $providerName
     * @param string $providerId
     * @return UserSocialAccount|null
     */
    public function findAccountByProvider(string $providerName, string $providerId): ?UserSocialAccount
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();

        $query = $queryBuilder
            ->select('user')
            ->from(UserSocialAccount::class, 'user')
            ->where('provider.name = :providerName')
            ->andWhere('provider.accountId = :providerId')
            ->setParameter('providerName',$providerName)
            ->setParameter('providerId', $providerId)
            ->getQuery();

        $socialAccount = $query->getOneOrNullResult();

        if (!$socialAccount) {
            throw new UserNotFoundException(null, "User with that social account does not exist");
        }

        return $socialAccount;
    }
}
