<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class UserCriteria
{
    private function __construct()
    {
    }

    public static function byId(string $param = ':id'): Predicate
    {
        return new Predicate('id = ' . $param);
    }

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byUsername(string $param = ':username'): Predicate
    {
        return new Predicate('username = ' . $param);
    }

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byEmail(string $param = ':email'): Predicate
    {
        return new Predicate('email = ' . $param);
    }
}
