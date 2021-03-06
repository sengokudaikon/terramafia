<?php

namespace App\Service\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserPersonal;
use App\Domain\User\Entity\VO\Gender;
use App\Domain\User\Repository\UserPersonalRepository;
use DateTimeImmutable;

class UserPersonalDataService
{
    private UserPersonalRepository $personalDataRepository;
    private UserService $userService;

    public function __construct(UserPersonalRepository $personalDataRepository, UserService $userService)
    {
        $this->personalDataRepository = $personalDataRepository;
        $this->userService = $userService;
    }

    public function addPersonalData(User $user, string $firstName, string $lastName, string $patronymic, string $birthdate, string $gender): void
    {
        $personalData = new UserPersonal(
            $user,
            $firstName,
            $lastName,
            $patronymic,
            new DateTimeImmutable($birthdate),
            new Gender($gender)
        );

        $this->personalDataRepository->add($personalData);
        $this->userService->addPersonalData($user, $personalData);
    }
}
