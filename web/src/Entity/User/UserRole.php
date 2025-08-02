<?php

declare(strict_types=1);

namespace App\Entity\User;

enum UserRole: int
{
    case USER = 0;
    case STAFF = 1;
    case ADMIN = 2;
    case OWNER = 3;
}
