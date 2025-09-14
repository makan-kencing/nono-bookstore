<?php

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class UserTokenCriteria
{
    public static function bySelector(string $param = ':selector', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'selector = ' . $param);
    }

    public static function byType(string $param = ':type', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'type = ' . $param);
    }

    public static function notExpired(?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'expires_at > NOW()');
    }
}
