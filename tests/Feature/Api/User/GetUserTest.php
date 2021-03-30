<?php

namespace Tests\Feature\Api\User;

use App\Helpers\Response;
use App\Service\User\UserService;
use Tests\Auth;
use Tests\TestCase;
use Tests\Unit\User\UserBuilder;

class GetUserTest extends TestCase
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

    public function testGetMe()
    {
        $user = $this->userBuilder->build();
        $user->getActivity()->verifyEmail();
        $response = $this->apiAs($user)->get(route('api.v1.me.get'));

        $response->assertStatus(Response::HTTP_SUCCESS);
        $response->assertJsonStructure(
            [
                'data' => [
                    'id',
                    'playerName',
                    'firstName',
                    'lastName',
                    'patronymic',
                    'birthday',
                    'gender',
                ],
            ]);
    }

    public function testGetByUuid()
    {
        $user = $this->userBuilder->build();
        $user->getActivity()->verifyEmail();

        $response = $this->apiAs($user)->get(route('api.v1.users.get', [
            'uuid' => $user->getExternalisedUuid(),
        ]));

        $response->assertStatus(Response::HTTP_SUCCESS);
    }

    public function testGetAll()
    {
        $userOne = $this->userBuilder->build();
        $userOne->getActivity()->verifyEmail();

        $userTwo = $this->userBuilder->build();
        $userTwo->getActivity()->verifyEmail();

        $response = $this->apiAs($userOne)->get(route('api.v1.users.list'));

        $response->assertStatus(Response::HTTP_SUCCESS);
    }
}
