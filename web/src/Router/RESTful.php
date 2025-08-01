<?php

declare(strict_types=1);

namespace App\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RESTful
{
    public function __construct()
    {
    }
}
