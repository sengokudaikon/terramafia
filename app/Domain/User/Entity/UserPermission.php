<?php

namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность ACL разрешений.
 *
 * @ORM\Entity(
 *     repositoryClass="App\Domain\User\Repository\UserPermissionRepository"
 * )
 * @ORM\Table(name="users_permission")
 * @package App\Domain\User\Entity
 */
class UserPermission
{
    /**
     * @var int Идентификатор.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var string Уникальный ключ.
     *
     * @ORM\Column(type="string", unique=true)
     */
    private string $slug;

    /**
     * @var string|null Описание разрешения.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $description;

    /**
     * Конструктор сущности ACL разрешений.
     *
     * @param string $slug
     * @param string|null $description
     */
    public function __construct(string $slug, ?string $description = null)
    {
        $this->slug = $slug;
        $this->description = $description;
    }

    /**
     * @param string $slug
     * @return self
     * @see Permission::$slug
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param string|null $description
     * @return self
     * @see Permission::$description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     * @see Permission::$id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     * @see Permission::$slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string|null
     * @see Permission::$description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
