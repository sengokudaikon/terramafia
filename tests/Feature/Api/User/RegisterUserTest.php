<?php

namespace Tests\Feature\Api\User;

use App\Domain\User\Entity\VO\SocialProvider;
use App\Helpers\Response;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Mockery;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Contracts\Provider;

/**
 * Тест регистрации пользователя.
 *
 * @package Tests\Feature\Api\User
 */
class RegisterUserTest extends TestCase
{
    /**
     * Email тестового пользователя.
     */
    const TEST_USER_EMAIL = 'testuser@plandi.ru';

    /**
     * Имя тестового пользователя.
     */
    const TEST_USER_NAME = 'Test User';

    /**
     * Пароль тестового пользователя.
     */
    const TEST_USER_PASSWORD = '123456';

    /**
     * Инициализация переменных.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->beginTransaction();
    }

    /**
     * Сброс переменных.
     */
    public function tearDown(): void
    {
        $this->rollbackTransaction();
        parent::tearDown();
    }

    /**
     * Проверяет регистрацию через email.
     * Тестовые данные пользователя.
     * Пользователь успешно зарегистрирован.
     */
    public function testRegisterUser(): void
    {
        $response = $this->post(route('api.v1.users.register', [
            'email' => $this->getFaker()->email,
            'password' => $this->getFaker()->password,
            'playerName' => $this->getFaker()->firstName
        ]));

        $response->assertStatus(Response::HTTP_SUCCESS);
        $response->assertJsonStructure(['message']);
    }

    /**
     * Проверяет успешное получение ссылки для авторизации через соц. сеть.
     * Тестовый пользователь.
     * Ссылка успешно возвращена.
     *
     * @dataProvider getSocialAuthUrlDataProvider
     * @param string $provider
     * @param string $state
     */
    public function testGetSocialAuthUrl(string $provider, string $state)
    {
        $requestParams = ['provider' => $provider, 'state' => $state];
        $response = $this->get(route('api.v1.auth.login.social', $requestParams), [
            'name' => self::TEST_USER_NAME,
            'email' => self::TEST_USER_EMAIL,
            'password' => self::TEST_USER_PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_SUCCESS);
        $response->assertJsonStructure(['redirectUrl']);
    }

    /**
     * Проверяет обработку результата запроса авторизации пользователя через соц. аккаунт.
     * Тестовый пользователь.
     * Код успешно получен.
     *
     * @dataProvider handleSocialAuthCallbackDataProvider
     * @param string $providerName
     */
    public function testHandleSocialAuthCallback(string $providerName)
    {
        $abstractUser = Mockery::mock(User::class);
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(mt_rand())
            ->shouldReceive('getName')
            ->andReturn(Str::random(10))
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10) . '@bbookriver.ru')
            ->shouldReceive('getAvatar')
            ->andReturn('https://picsum.photos/200')
            ->shouldReceive('getRaw')
            ->andReturn([]);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('stateless->user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with($providerName)->andReturn($provider);

        $requestParams = ['provider' => $providerName];
        $response = $this->post(route('api.v1.auth.login.social.callback', $requestParams), [
            'code' => '1234',
        ]);

        $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'token',
            'ttl',
            'uuid',
        ]);
    }

    /**
     * Data provider для теста получения ссылки авторизации через соц. сеть.
     *
     * @return array[]
     */
    public function getSocialAuthUrlDataProvider()
    {
        return [
            [SocialProvider::GOOGLE, 'https://localhost:3000'],
            [SocialProvider::FACEBOOK, ''],
            [SocialProvider::VKONTAKTE, '{"key": 123}'],
        ];
    }

    /**
     * Data provider для теста обработки результата запроса.
     *
     * @return array[]
     */
    public function handleSocialAuthCallbackDataProvider()
    {
        return [
            [SocialProvider::GOOGLE],
            [SocialProvider::FACEBOOK],
            [SocialProvider::VKONTAKTE],
        ];
    }
}
