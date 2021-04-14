<?php

namespace App\Models;

use App\Helpers\UuidExternaliser;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Trait UUIDable
 * @property-read UuidInterface $uuid
 * @package App\Models
 */
trait UUIDable
{
    protected UuidInterface $uuid;

    protected function uuid()
    {
        return (new UuidExternaliser())->externalise($this->uuid);
    }

    protected function identify(): void
    {
        $this->uuid = Uuid::uuid4();
    }
}
