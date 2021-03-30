<?php

use App\Domain\User\Repository\IUserRepository;
use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserPermission;
use Faker\Generator;
use App\Domain\User\Repository\IUserPermissionRepository;
use App\Service\User\UserService;
use App\Domain\User\Entity\VO\Role;
use Illuminate\Support\Arr;
use Faker\Factory;
use Illuminate\Support\Str;

/**
 * Класс наполнения таблицы пользователей.
 *
 */
class UsersTableSeeder extends Seeder
{
    /**
     * @var string Тестовый email администратора.
     */
    const TEST_ADMIN_EMAIL = 'cryogen.sxu@gmail.com';

    /**
     * @var string Тестовое имя администратора.
     */
    const TEST_ADMIN_NAME = 'Тестовый администратор';

    /**
     * @var string Тестовый пароль.
     */
    const TEST_PASSWORD = '123456';

    /**
     * @var string Тестовый email пользователя.
     */
    const TEST_USER_EMAIL = 'acidec@outlook.com';

    /**
     * @var string Тестовое имя игрока.
     */
    const TEST_USER_NAME = 'Тестовый игрок';

    /**
     * @var string Тестовое имя пользователя.
     */
    const TEST_SECOND_USER_NAME = 'Второй тестовый игрок';

    /**
     * @var string Тестовый email пользователя.
     */
    const TEST_SECOND_USER_EMAIL = 'cry0gen@hotmail.com';

    /**
     * @var EntityManagerInterface Менеджер сущностей.
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var IUserRepository Репозиторий пользователей.
     */
    private IUserRepository $userRepository;

    /**
     * @var array|UserPermission[]
     */
    private array $permissions;

    /**
     * @var Generator Генератор случайных данных.
     */
    private Generator $faker;

    /**
     * @var string Хэшированый тестовый пароль.
     */
    private string $testHashedPassword;

    /**
     * UsersTableSeeder constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param IUserRepository $userRepository
     * @param IUserPermissionRepository $permissionRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        IUserRepository $userRepository,
        IUserPermissionRepository $permissionRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->permissions = $permissionRepository->findAll();
        $this->testHashedPassword = UserService::hashPassword(self::TEST_PASSWORD);
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Запускает сидирование.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function run(): void
    {
        $this->createUser(self::TEST_ADMIN_NAME, self::TEST_ADMIN_EMAIL, new Role(Role::ADMIN));
        $this->createUser(self::TEST_USER_NAME, self::TEST_USER_EMAIL, new Role(Role::PLAYER));

        for ($i = 0; $i < 100; $i++) {
            $this->createUser(
                $this->faker->firstName,
                Str::random(5) . $this->faker->email,
                new Role(Arr::random(Role::getValues()))
            );
        }

        $this->createUser(
            self::TEST_SECOND_USER_NAME,
            self::TEST_SECOND_USER_EMAIL,
            new Role(Role::PLAYER)
        );
        $this->entityManager->flush();
    }

    /**
     * Создает сущность пользователя.
     *
     * @param string $name
     * @param string $email
     * @param Role $role
     * @return User
     * @throws \Doctrine\ORM\ORMException
     */
    public function createUser(string $name, string $email, Role $role): User
    {
        $user = new User(
            $name,
            $email,
            $this->testHashedPassword,
            $role
        );
        $usersTestEmails = [self::TEST_ADMIN_EMAIL, self::TEST_USER_EMAIL];

        $user->activate();

        if (in_array($email, $usersTestEmails)) {
            $user->getActivity()->confirm();
            $user->getActivity()->verifyEmail();
        }

        $this->userRepository->add($user);

        return $user;
    }
}
