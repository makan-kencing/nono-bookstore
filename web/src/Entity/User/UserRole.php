<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Utils\EnumUtils;

enum UserRole: int
{
    use EnumUtils;

    case USER = 0;
    case STAFF = 1;
    case ADMIN = 2;
    case OWNER = 3;
}
