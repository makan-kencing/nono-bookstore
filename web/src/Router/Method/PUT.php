<?php

declare(strict_types=1);

namespace App\Router\Method;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class PUT extends Method
{
    public const string METHOD = "PUT";

    public function __construct()
    {
    }
}
