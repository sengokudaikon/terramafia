<?php

namespace App\Attributes;

use Attribute;

#[Attribute(\Attribute::TARGET_CLASS)]class ModelShape
{
    public function __construct(array $shape)
    {
    }
}
