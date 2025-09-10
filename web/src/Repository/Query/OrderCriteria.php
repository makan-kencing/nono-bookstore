<?php

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class OrderCriteria
{
    private function __construct()
    {
    }

    public static function byId(string $param = ':id', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'id = ' . $param);
    }

    public static function byUserId(string $param = ':user_id', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'user_id = ' . $param);
    }
}
