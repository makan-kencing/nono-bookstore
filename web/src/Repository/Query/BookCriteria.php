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
     * @param ?string $alias
     * @return Predicate
     */
    public static function byIsbn(string $param = ':isbn', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'isbn = ' . $param);
    }

    public static function notSoftDeleted(?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'deleted_at IS NULL');
    }

    /**
     * @param literal-string $param
     * @param ?string $alias
     * @return Predicate
     */
    public static function byPublisher(string $param = ':publisher', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'publisher = ' . $param);
    }

    /**
     * @param literal-string $param
     * @param ?string $alias
     * @return Predicate
     */
    public static function byLanguage(string $param = ':language', ?string $alias = null): Predicate
    {
        if ($alias === null) $alias = '';
        else $alias .= '.';

        return new Predicate($alias . 'language = ' . $param);
    }
}
