<?php

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Инициализация переменных.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Инициализирует транзакции.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function beginTransaction(): void
    {
        $this->app->make(EntityManagerInterface::class)->beginTransaction();
    }

    /**
     * Выполняет откат транзакций, инициализированных методом beginTransaction.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function rollbackTransaction(): void
    {
        $this->app->make(EntityManagerInterface::class)->rollback();
    }

    /**
     * Возвращает объект фейкера.
     *
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return Factory::create('ru_RU');
    }

    /**
     * Аутентифицирует пользователя для запроса в API.
     *
     * @param $user
     * @return $this
     */
    public function apiAs($user)
    {
        $this->actingAs($user)->withHeader('Authorization', 'Bearer ' . JWTAuth::fromUser($user));

        return $this;
    }

//    public function getSwaggerValidator()
//    {
//        return ResponseValidatorBuilder::fromJson(storage_path('api-docs/api-docs.json'))->getValidator();
//    }
}
