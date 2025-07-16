<?php

namespace App\Entity\User;

enum UserStatus
{
    case SUCCESS;
    case TWO_FACTOR_REQUIRED;
    case LOCKED;
    case FAILED;
}
