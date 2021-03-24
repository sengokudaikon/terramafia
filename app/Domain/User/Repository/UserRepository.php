<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Helpers\UuidExternaliser;

class UserRepository extends BaseRepository implements IUserRepository
{
    /**
     * @var UuidExternaliser
     */
    protected UuidExternaliser $uuidDecoder;

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    public function update(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush($user);
    }

    public function findUserByUuid(string $userId): User
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();

        $query = $queryBuilder
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $this->uuidDecoder->internalise($userId))
            ->getQuery();

        /** @var User $user */
        $user = $query->getOneOrNullResult();
        if (!$user) {
            throw new UserNotFoundException($userId);
        }

        return $user;
    }

    public function checkEmailExists(string $user_id, string $email): bool
    {
        $query = (int)$this->entityManager
            ->createQueryBuilder()
            ->select("COUNT(user)")
            ->from(User::class, 'user')
            ->where('lower(user.email) = :email AND user.id != :user_id')
            ->setParameters(
                [
                    'email' => mb_strtolower($email),
                    'user_id' => $user_id
                ])
            ->getQuery()
            ->getSingleScalarResult();

        return $query != 0;
    }

    public function findUserByEmail(string $userEmail): User
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();

        $query = $queryBuilder
            ->select('user')
            ->from(User::class, 'user')
            ->where('lower(user.email) = :email')
            ->getQuery();

        $query->setParameter('email', mb_strtolower($userEmail));

        $user = $query->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException(null, "User with email {$userEmail} does not exist");
        }

        return $user;
    }

    public function findAllUsers(): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();

        $queryBuilder->select('user')
            ->from(User::class, 'user');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findUsersByIds(array $ids): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where($qb->expr()->in('u.id', ':ids'));

        $qb->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }
}
