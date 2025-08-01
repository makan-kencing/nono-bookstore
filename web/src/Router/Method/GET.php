<?php

declare(strict_types=1);

namespace App\Router\Method;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class GET extends Method
{
    public const string METHOD = "GET";

    public function __construct()
    {
    }
}
