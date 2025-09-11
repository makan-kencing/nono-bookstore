<?php

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class UserProfileCriteria
{
    private function __construct()
    {
    }

    /**
     * @param string $param
     * @param string|null $alias
     * @return Predicate
     */
    public static function byId(string $param = ':id', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'id = ' . $param);
    }

    /**
     * @param string $param
     * @param string|null $alias
     * @return Predicate
     */
    public static function byContactNo(string $param = ':contact_no', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'contact_no = ' . $param);
    }

    /**
     * @param string $param
     * @param string|null $alias
     * @return Predicate
     */
    public static function byDateOfBirth(string $param = ':date_of_birth', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'date_of_birth = ' . $param);
    }
}


