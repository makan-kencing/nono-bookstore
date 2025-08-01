<?php

declare(strict_types=1);

namespace App\Router;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Path
{
    public string $path;

    public function __construct(string $route)
    {
        $this->path = $route;
    }
}
