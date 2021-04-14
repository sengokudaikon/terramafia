<?php

declare(strict_types=1);

namespace App\Enums;

use Tailflow\Enum\Enum;

class Role extends Enum
{
    public const User = 'user';
    public const Admin = 'admin';
}
