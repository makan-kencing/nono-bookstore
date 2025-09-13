<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class UserCriteria
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

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byUsername(string $param = ':username', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'username = ' . $param);
    }

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byUsernameLike(string $param = ':username', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'username LIKE ' . $param);
    }

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byEmail(string $param = ':email', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate('email = ' . $param);
    }
}
