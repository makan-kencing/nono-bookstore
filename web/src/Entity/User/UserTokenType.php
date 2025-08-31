<?php

declare(strict_types=1);

namespace App\Entity\User;

enum UserTokenType
{
    case REMEMBER_ME;
    case RESET_PASSWORD;
    case CONFIRM_EMAIL;
}
