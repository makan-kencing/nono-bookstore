<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Orm\Expr\Predicate;

class BookCriteria
{
    private function __construct()
    {
    }

    /**
     * @param literal-string $param
     * @return Predicate
     */
    public static function byIsbn(string $param = ':isbn'): Predicate
    {
        return new Predicate('b.isbn = ' . $param);
    }

    public static function notSoftDeleted(): Predicate
    {
        return new Predicate('b.deleted_at IS NULL');
    }
}
