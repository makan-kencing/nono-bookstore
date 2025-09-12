<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class UserProfileCriteria
{
    private function __construct()
    {
    }

    public static function byUserId(string $param = ':user_id', ?string $alias = 'up'): Predicate
    {
        if ($alias === null) {
            $alias = '';
        } else {
            $alias .= '.';
        }

        return new Predicate($alias . 'user_id = ' . $param);
    }

    public static function byId(string $param = ':id', ?string $alias = 'up'): Predicate
    {
        if ($alias === null) {
            $alias = '';
        } else {
            $alias .= '.';
        }

        return new Predicate($alias . 'id = ' . $param);
    }
}
