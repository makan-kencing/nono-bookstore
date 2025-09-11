<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class AuthorCriteria
{
    private function __construct()
    {
    }

    /**
     * @param literal-string $param
     * @param ?string $alias
     * @return Predicate
     */
    public static function byId(string $param = ':id', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'id = ' . $param);
    }

    /**
     * @param literal-string $param
     * @param ?string $alias
     * @return Predicate
     */
    public static function byNameMatch(string $param = ':name', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate('MATCH (' . $alias . 'name) AGAINST(' . $param . ')');
    }
}
