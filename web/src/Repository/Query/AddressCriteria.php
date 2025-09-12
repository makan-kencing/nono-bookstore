<?php

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class AddressCriteria
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
}
