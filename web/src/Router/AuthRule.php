<?php

declare(strict_types=1);

namespace App\Router;

use App\Entity\User\UserRole;

enum AuthRule
{
    case EXACT;
    case HIGHER;
    case LOWER;
    case HIGHER_OR_EQUAL;
    case LOWER_OR_EQUAL;

    public function check(UserRole $r1, UserRole $r2): bool
    {
        return match ($this) {
            AuthRule::EXACT => $r1 == $r2,
            AuthRule::HIGHER => $r1->value > $r2->value,
            AuthRule::LOWER => $r1->value < $r2->value,
            AuthRule::HIGHER_OR_EQUAL => $r1->value >= $r2->value,
            AuthRule::LOWER_OR_EQUAL => $r1->value <= $r2->value
        };
    }
}
