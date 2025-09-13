<?php

namespace App\Repository\Query;

class UserTokenCriteria
{
    public static function bySelector(string $alias = 'ut'): string
    {
        return "$alias.selector = :selector";
    }

    public static function notExpired(string $alias = 'ut'): string
    {
        return "$alias.expires_at > NOW()";
    }
}
