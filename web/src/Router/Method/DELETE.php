<?php

declare(strict_types=1);

namespace App\Router\Method;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class DELETE extends Method
{
    public const string METHOD = "DELETE";

    public function __construct()
    {
    }
}
