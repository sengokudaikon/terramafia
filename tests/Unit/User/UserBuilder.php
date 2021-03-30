<?php

namespace Tests\Unit\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\VO\Role;
use App\Domain\User\Repository\UserRepository;
use App\Helpers\SecurityHashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Auth;
use Tests\FakerTool;

class UserBuilder
{
    use FakerTool, Auth;

    private User $user;

    /**
     * Конструктор строителя сущности пользователя.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->user = new User(
            $this->getFaker()->jobTitle,
            $this->getFaker()->email,
            SecurityHashHelper::generatePasswordHash('123456'),
            new Role(Role::PLAYER)
        );

        $this->user->activate();
    }

    /**
     * Игровое имя.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->user->setPlayerName($name);

        return $this;
    }

    /**
     * Почта.
     *
     * @param string $email
     *
     * @return $this
     */
    public function email(string $email): self
    {
        $this->user->setEmail($email);

        return $this;
    }

    /**
     * Возвращает построеную сущность.
     *
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function build(): User
    {
        $userRepository = resolve(UserRepository::class);
        $entityManager = resolve(EntityManagerInterface::class);
        $userRepository->add($this->user);
        $entityManager->flush();

        return $this->user;
    }
}
