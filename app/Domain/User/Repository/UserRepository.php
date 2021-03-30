<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Helpers\UuidExternaliser;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserRepository
 *
 * @package App\Domain\User\Repository
 */
class UserRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $persister = $this->getEntityManager()->getUnitOfWork()->getEntityPersister(User::class);

        return $persister->load($criteria, null, null, [], null, 1, $orderBy);
    }

    public function find(string $id, string $entityClass = null): ?object
    {
        return parent::find($id, User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    public function update(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush($user);
    }

    public function findUserByUuid(string $userId): User
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $query = $queryBuilder
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', (new UuidExternaliser)->internalise($userId))
            ->getQuery();

        /** @var User $user */
        $user = $query->getOneOrNullResult();
        if (!$user) {
            throw new UserNotFoundException($userId);
        }

        return $user;
    }

    public function checkEmailExists(string $userId, string $email): bool
    {
        $query = (int)$this->getEntityManager()
            ->createQueryBuilder()
            ->select("COUNT(user)")
            ->from(User::class, 'user')
            ->where('lower(user.email) = :email AND user.id != :user_id')
            ->setParameters(
                [
                    'email' => mb_strtolower($email),
                    'user_id' => $userId
                ]
            )
            ->getQuery()
            ->getSingleScalarResult();

        return $query != 0;
    }

    public function findUserByEmail(string $userEmail): User
    {
        $queryBuilder = $this->getEntityManager()
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
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $queryBuilder->select('user')
            ->from(User::class, 'user');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findUsersByIds(array $ids): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where($qb->expr()->in('u.id', ':ids'));

        $qb->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }
}
