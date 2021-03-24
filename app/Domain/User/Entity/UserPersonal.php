<?php

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\VO\Gender;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Персональные данные пользователя.
 *
 * @ORM\Entity(
 *     repositoryClass="App\Domain\User\Repository\UserPersonalRepository"
 * )
 * @ORM\Table(name="users_user_personal")
 * @package App\Domain\User\Entity
 */
class UserPersonal
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
     * @var User Пользователь.
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Domain\User\Entity\User",
     *     inversedBy="personal"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private User $user;

    /**
     * @var string|null Имя.
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private ?string $firstName;

    /**
     * @var string|null Фамилия.
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private ?string $lastName;

    /**
     * @var string|null Отчество.
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private ?string $patronymic;

    /**
     * @var DateTimeImmutable|null Дата рождения.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $birthday;

    /**
     * @var Gender|null Пол.
     *
     * @ORM\Column(
     *     type="App\Domain\User\Entity\VO\Gender"
     * )
     */
    private ?Gender $gender;

    /**
     * Конструктор персональных данных.
     *
     * @param User                   $user
     * @param string|null            $firstName
     * @param string|null            $lastName
     * @param string|null            $patronymic
     * @param DateTimeImmutable|null $birthday
     * @param Gender|null            $gender
     */
    public function __construct(
        User $user,
        ?string $firstName,
        ?string $lastName,
        ?string $patronymic,
        ?DateTimeImmutable $birthday,
        ?Gender $gender
    ) {
        $this->user = $user;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->patronymic = $patronymic;
        $this->birthday = $birthday;
        $this->gender = $gender;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return UserPersonal
     * @see UserPersonal::$firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string|null $lastName
     * @return self
     * @see UserPersonal::$lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string|null $patronymic
     * @return self
     * @see UserPersonal::$patronymic
     */
    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * @param DateTimeImmutable|null $birthday
     * @return self
     * @see UserPersonal::$birthday
     */
    public function setBirthday(?DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @param Gender|null $gender
     *
     * @return self
     * @see UserPersonal::$gender
     */
    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return int
     * @see UserPersonal::$id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     * @see UserPersonal::$user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string|null
     * @see UserPersonal::$lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     * @see UserPersonal::$patronymic
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @return DateTimeImmutable|null
     * @see UserPersonal::$birthday
     */
    public function getBirthday(): ?DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @return Gender|null
     * @see UserPersonal::$gender
     */
    public function getGender(): ?Gender
    {
        return $this->gender;
    }
}
