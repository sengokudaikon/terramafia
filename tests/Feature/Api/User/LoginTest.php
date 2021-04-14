<?php

namespace Tests\Feature\Api\User;

use App\Helpers\Response;
use App\Service\User\UserService;
use Tests\Auth;
use Tests\TestCase;
use Database\Seeders\UsersTableSeeder;
use Tests\Unit\User\UserBuilder;

class LoginTest extends TestCase
{
    use Auth;

    /**
     * @var UserService|null Сервис пользователей.
     */
    private ?UserService $userService;

    private ?UserBuilder $userBuilder;

    /**
     * Инициализация переменных.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->beginTransaction();
        $this->userService = resolve(UserService::class);
        $this->userBuilder = new UserBuilder();
    }

    /**
     * Сброс переменных.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function tearDown(): void
    {
        $this->rollbackTransaction();
        parent::tearDown();
        $this->userService = null;
        $this->userBuilder = null;
    }
    /**
     * Проверка авторизации с правильными учетными данными.
     *
     * @covers \App\Http\Controllers\Api\V1\User\UserAuthController::login
     */
    public function testPositiveLogin(): void
    {
        $user = $this->userBuilder->build();
        $user->getActivity()->verifyEmail();

        $response = $this->withHeaders([
                                           'Accept' => 'application/json'
                                       ])->post(route('api.v1.auth.login', [
            'email' => $user->getEmail(),
            'password' => '123456',
            'rememberMe' => false
        ]));

        $response->assertStatus(Response::HTTP_SUCCESS);
        $response->assertJsonStructure(['message', 'token', 'ttl', 'uuid']);
        $response->assertJson(['ttl' => config('jwt.ttl')]);
        $response->assertHeaderMissing('cookies');
    }


    /**
     * Проверка авторизации с флагом "Запомнить меня".
     *
     * @covers \App\Http\Controllers\Api\V1\User\UserAuthController::login
     */
    public function testLoginWithRememberFlag(): void
    {
        $user = $this->userBuilder->build();
        $user->getActivity()->verifyEmail();

        $response = $this->withHeaders([
                                           'Accept' => 'application/json'
                                       ])->post(route('api.v1.auth.login', [
            'email' => $user->getEmail(),
            'password' => '123456',
            'rememberMe' => true
        ]));

        $response->assertStatus(Response::HTTP_SUCCESS);
        $response->assertJsonStructure(['message', 'token', 'ttl', 'uuid']);
        self::assertTrue(config('jwt.ttl') !== config('jwt.remember_me_ttl'));
        $response->assertJson(['ttl' => config('jwt.remember_me_ttl')]);
        $response->assertHeaderMissing('cookies');
    }

    /**
     * Проверка авторизации с неправильным логином.
     *
     * @covers \App\Http\Controllers\Api\V1\User\UserAuthController::login
     */
    public function testBadLogin(): void
    {
        $response = $this->withHeaders([
                                           'Accept' => 'application/json'
                                       ])->post(route('api.v1.auth.login', [
            'email' => '',
            'password' => UsersTableSeeder::TEST_PASSWORD,
            'rememberMe' => false
        ]));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors']);
    }
}
