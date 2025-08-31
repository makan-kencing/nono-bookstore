<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Book;
use App\Orm\QueryBuilder;

class BookQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Book>
     */
    public static function withFullDetails(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Book::class, 'b')
            ->join('publisher', 'p')
            ->leftJoin($qb->createJoin('images', 'bi')
                ->leftJoin('file', 'f'))
            ->join($qb->createJoin('authors', 'ad')
                ->join('author', 'a'))
            ->leftJoin($qb->createJoin('categories', 'cd')
                ->leftJoin('category', 'c'))
            ->leftJoin($qb->createJoin('series', 'sd')
                ->leftJoin('series', 's'));

        return $qb;
    }
}
