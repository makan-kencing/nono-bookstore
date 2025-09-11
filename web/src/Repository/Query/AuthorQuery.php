<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Author\Author;
use App\Orm\QueryBuilder;

class AuthorQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Author>
     */
    public static function minimal(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Author::class, 'a');
        return $qb;
    }
}
