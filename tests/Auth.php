<?php

namespace Tests;

use App\Domain\User\Entity\User;
use App\Helpers\DI;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

/**
 * Трейт авторизации.
 *
 * @package Tests
 */
trait Auth
{
    use MakesHttpRequests;

    /**
     * Авторизация, как пользователь.
     *
     * @return string
     */
    public function authByUser(): string
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.v1.auth.login', [
            'email' => UsersTableSeeder::TEST_USER_EMAIL,
            'password' => UsersTableSeeder::TEST_PASSWORD,
            'gRecaptchaResponse' => 'test',
            'rememberMe' => 0,
        ]));

        return $response->json('token');
    }

    /**
     * Возвращает тестового пользователя.
     *
     * @return User
     */
    public function getUser(): User
    {
        return DI::getUserService()->findByEmail(UsersTableSeeder::TEST_USER_EMAIL);
    }

    /**
     * Возвращает тестового пользователя с ролью администратор.
     *
     * @return User
     */
    public function getAdmin(): User
    {
        return DI::getUserService()->findByEmail(UsersTableSeeder::TEST_ADMIN_EMAIL);
    }

    /**
     * Возвращает второго тестового пользователя.
     *
     * @return User
     */
    public function getSecondUser(): User
    {
        return DI::getUserService()->findByEmail(UsersTableSeeder::TEST_SECOND_USER_EMAIL);
    }
}
