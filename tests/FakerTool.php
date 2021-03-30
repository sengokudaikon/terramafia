<?php

namespace Tests;
use Faker\Factory;
use Faker\Generator;

trait FakerTool
{
    /**
     * Возвращает объект фейкера.
     *
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return Factory::create('ru_RU');
    }
}
