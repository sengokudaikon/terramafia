<?php

namespace App\Service;

class RatingCalculator
{
public const VICTORY = ['city' => 30, 'mafia' => 40, 'don' => 20, 'sheriff' => 20];
public const ADD = ['guess_city' => 15, 'guess_mafia' => 20, 'sheriff_all_checks' => 30, 'sheriff_fb' => 10, '2/3' => 15, '3/3' => 20];
public const WITHDRAW = '-30';

// TODO настройка в админке из конфига
}
