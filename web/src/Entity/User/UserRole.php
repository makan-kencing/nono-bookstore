<?php

declare(strict_types=1);

namespace App\Entity\User;

enum UserRole
{
    case USER;
    case STAFF;
    case ADMIN;
    case OWNER;
}
