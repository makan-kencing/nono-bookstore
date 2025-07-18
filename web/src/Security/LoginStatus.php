<?php

declare(strict_types=1);

namespace App\Security;

enum LoginStatus
{
    case SUCCESS;
    case TWO_FACTOR_REQUIRED;
    case LOCKED;
    case FAILED;
}
