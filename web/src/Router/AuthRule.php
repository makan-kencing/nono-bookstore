<?php

declare(strict_types=1);

namespace App\Router;

enum AuthRule
{
    case EXACT;
    case HIGHER;
    case LOWER;
}
