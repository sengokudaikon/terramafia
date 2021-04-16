<?php

declare(strict_types=1);

namespace App\Enums;

use Tailflow\Enum\Enum;

class HealthCheckStatus extends Enum
{
    public const Ok = 'ok';
    public const DatabaseDown = 'database_down';
    public const CacheDown = 'cache_down';
    public const AllSystemsDown = 'all_systems_down';
}
