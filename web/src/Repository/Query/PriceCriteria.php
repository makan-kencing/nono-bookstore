<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class PriceCriteria
{
    private function __construct()
    {
    }

    /**
     * @param literal-string $paramLow
     * @param literal-string $paramHigh
     * @param ?string $alias
     * @return Predicate
     */
    public static function byAmountBetween(string $paramLow = ':min', string $paramHigh = ':max', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'amount BETWEEN ' . $paramLow . ' AND ' . $paramHigh);
    }
}
