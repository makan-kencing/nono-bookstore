<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class RatingCriteria
{
    private function __construct()
    {
    }

    public static function byWork(string $param = ':work_id', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'work_id = ' . $param);
    }
}
