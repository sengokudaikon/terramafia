<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Helpers\UuidExternaliser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class UserRepository
 * @method findOneBy(array $criteria, array $orderBy = null)
 * @package App\Domain\User\Repository
 */
class UserRepository extends BaseRepository
{
    /**
     * @var UuidExternaliser
     */
    protected UuidExternaliser $uuidDecoder;

    public function __construct(UuidExternaliser $uuidDecoder, EntityManagerInterface $entityManager)
    {
        $this->uuidDecoder = $uuidDecoder;
        $this->entityManager = $entityManager;
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
            ->setParameter('uuid', $this->uuidDecoder->internalise($userId))
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

        return $queryBuilder->getQuery()->getArrayResult();
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
