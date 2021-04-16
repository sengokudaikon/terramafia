<?php

namespace App\Enums;

use Tailflow\Enum\Enum;

class GameRole extends Enum
{
public const Citizen = 'city';
public const Mafia = 'mafia';
public const Godfather = 'don';
public const Detective = 'sheriff';
}
