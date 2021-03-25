<?php

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\VO\Role;
use App\Helpers\UuidExternaliser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Пользователь.
 *
 * @ORM\Entity(
 *     repositoryClass="App\Domain\User\Repository\UserRepository"
 * )
 * @ORM\Table(name="users_user")
 * @package App\Domain\User\Entity
 */
class User implements JWTSubject, Authenticatable
{
    /**
     * @var string Название поля с идентификатором пользователя.
     */
    public const ID_FIELD_NAME = 'id';

    /**
     * @var string Название поля с remember токеном.
     */
    public const REMEMBER_TOKEN_FIELD_NAME = 'remember_token';

    /**
     * @var int Идентификатор.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var UuidInterface Уникальный идентификатор.
     *
     * @ORM\Column(type="guid", length=36, unique=true)
     */
    protected UuidInterface $uuid;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getExternalisedUuid(): string
    {
        return (new UuidExternaliser())->externalise($this->getUuid());
    }

    protected function identify(): void
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @var string|null Игровое имя.
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $playerName;

    /**
     * @var string|null Email.
     *
     * @ORM\Column(type="string", length=129, unique=true, nullable=true)
     */
    private ?string $email;

    /**
     * @var string Пароль (hash).
     *
     * @ORM\Column(type="string", length=60)
     */
    private string $password;

    /**
     * @var Role Роль.
     *
     * @ORM\Column(type="role")
     */
    private Role $role;

    /**
     * @var string|null Токен при аутентификации с флагом "запомнить".
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private ?string $rememberToken;

    /**
     * @var Collection|UserSocialAccount[] Социальные аккаунты пользователя.
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\User\Entity\UserSocialAccount",
     *     mappedBy="user",
     *     cascade={"remove"}
     * )
     */
    private Collection $socialAccounts;

    /**
     * @var Collection|UserPermission[] ACL разрешения пользователя.
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Domain\User\Entity\UserPermission"
     * )
     * @ORM\JoinTable(
     *     name="users_user_permission",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     * )
     */
    private Collection $permissions;

    /**
     * @var UserPersonal|null Персональные данные.
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Domain\User\Entity\UserPersonal",
     *     mappedBy="user"
     * )
     */
    private ?UserPersonal $personal;

    /**
     * @var UserActivity|null
     *
     * @ORM\Embedded(class="App\Domain\User\Entity\UserActivity", columnPrefix="activity")
     */
    private ?UserActivity $activity;

    public function __construct(
        ?string $playerName,
        ?string $email,
        string $password,
        Role $role
    ) {
        $this->identify();

        $this->playerName = $playerName;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;

        $this->socialAccounts = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

//    public static function signUp(string $playerName, string $email, string $password,  Role $role): User
//    {
//        return new self($playerName, $email, $password, $role);
//    }

    public function activate(): void
    {
        $this->activity = new UserActivity();
    }

    /**
     * @return string|null
     */
    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    /**
     * @param string|null $playerName
     *
     * @return User
     */
    public function setPlayerName(?string $playerName): self
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * @return string|null
     * @see User::$email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return self
     * @see User::$email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return self
     * @see User::$password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Role
     * @see User::$role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Collection|UserSocialAccount[]
     * @see User::$socialAccounts
     */
    public function getSocialAccounts(): Collection
    {
        return $this->socialAccounts;
    }

    /**
     * @return Collection|UserPermission[]
     * @see User::$permissions
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getType(): string
    {
        $fullClassName = explode("\\", strtolower(get_class($this)));
        return end($fullClassName);
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UserActivity
     */
    public function getActivity(): UserActivity
    {
        if (!$this->activity) {
            $this->activity = new UserActivity();
        }

        return $this->activity;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $rememberToken
     * @return $this
     */
    public function setRememberToken($rememberToken): User
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Добавлеяет ACL разрешение пользователю.
     *
     * @param UserPermission $permission
     */
    public function addPermission(UserPermission $permission): void
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function getJWTIdentifier(): int
    {
        return $this->getId();
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @return string
     * @see User::$rememberToken
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return self::ID_FIELD_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|mixed
     */
    public function getAuthIdentifier(): UuidInterface
    {
        return $this->getUuid();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->getPassword();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return self::REMEMBER_TOKEN_FIELD_NAME;
    }

    /**
     * @return UserPersonal|null
     * @see User::$personal
     */
    public function getPersonal(): ?UserPersonal
    {
        return $this->personal;
    }

    /**
     * @param UserPersonal|null $personal
     *
     * @return User
     * @see User::$personal
     */
    public function addPersonal(?UserPersonal $personal): self
    {
        $this->personal = $personal;

        return $this;
    }
}
