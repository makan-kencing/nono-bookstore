<?php

declare(strict_types=1);

namespace App\Service;

use App\Utils\EnumUtils;

enum LoginResult: int
{
    use EnumUtils;

    case INVALID = 0;
    case TWO_FACTOR_REQUIRED = 1;
    case SUCCESS = 2;
}
