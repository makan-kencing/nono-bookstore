<?php

declare(strict_types=1);

namespace App\Router\Method;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class POST extends Method
{
    public const HttpMethod METHOD = HttpMethod::POST;
}
