<?php

namespace App\Domain\User;

use App\Domain\User\Entity\EmailConfirmationToken;
use App\Domain\User\Entity\PasswordResetToken;
use App\Domain\User\Entity\Types\GenderType;
use App\Domain\User\Entity\Types\RoleType;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserPermission;
use App\Domain\User\Entity\UserPersonal;
use App\Domain\User\Entity\UserSocialAccount;
use App\Domain\User\Entity\VO\Gender;
use App\Domain\User\Entity\VO\Role;
use App\Domain\User\Repository\EmailConfirmationRepository;
use App\Domain\User\Repository\PasswordResetTokenRepository;
use App\Domain\User\Repository\UserPermissionRepository;
use App\Domain\User\Repository\UserPersonalRepository;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserSocialAccountRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerDoctrineTypes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->boot();
        $this->app->register(UserSocialProvider::class);
        $this->registerRepositories();
    }

    /**
     * Регистрация типов полей для Doctrine.
     *
     */
    private function registerDoctrineTypes(): void
    {
        if (!Type::hasType(Role::class)) {
            Type::addType(Role::class, RoleType::class);
        }

        if (!Type::hasType(Gender::class)) {
            Type::addType(Gender::class, GenderType::class);
        }
    }

    /**
     * Регистрирует репозитории модуля.
     */
    private function registerRepositories(): void
    {
        $this->app->bind(
            UserRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(User::class);
            }
        );
        $this->app->bind(
            UserPermissionRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(UserPermission::class);
            }
        );
        $this->app->bind(
            UserPersonalRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(UserPersonal::class);
            }
        );
        $this->app->bind(
            UserSocialAccountRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(UserSocialAccount::class);
            }
        );
        $this->app->bind(
            PasswordResetTokenRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(PasswordResetToken::class);
            }
        );
        $this->app->bind(
            EmailConfirmationRepository::class,
            function ($app) {
                return $app->make(EntityManagerInterface::class)
                    ->getRepository(EmailConfirmationToken::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
