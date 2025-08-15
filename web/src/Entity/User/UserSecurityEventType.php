<?php

declare(strict_types=1);

namespace App\Entity\User;

enum UserSecurityEventType
{
    case SUCCESSFUL_LOGIN;
    case ATTEMPTED_LOGIN;
    case LOGOUT;
    case CHANGED_PASSWORD;
    case CHANGED_EMAIL;
}
