<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Book;
use App\Orm\Entity;
use App\Orm\JoinBuilder;
use App\Orm\QueryBuilder;

class BookQuery
{
    private function __construct()
    {
    }

    /**
     * @param literal-string $property
     * @param string $alias
     * @return JoinBuilder<Entity, Book>
     */
    public static function bookDetailsPart(string $property, string $alias = ''): JoinBuilder
    {
        $jb = new JoinBuilder($property, $alias);
        $jb->join('work', $alias. 'w')
            ->leftJoin($jb->createJoin('images', $alias. 'bi')
                ->join('file', $alias . 'bif'))
            ->join($jb->createJoin('authors', $alias .'bad')
                ->join('author', $alias .'ba'))
            ->leftJoin('prices', $alias . 'p')
            ->leftJoin('inventories', $alias . 'i');
        return $jb;
    }

    /**
     * @return QueryBuilder<Book>
     */
    public static function asBookListing(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Book::class, 'b')
            ->leftJoin($qb->createJoin('images', 'bi')
                ->join('file', 'f'))
            ->join($qb->createJoin('authors', 'ad')
                ->join('author', 'a'))
            ->join($qb->createJoin('work', 'w')
                ->leftJoin($qb->createJoin('categories', 'cd')
                    ->join('category', 'c'))
                ->leftJoin($qb->createJoin('series', 'sd')
                    ->join('series', 's'))
                ->join($qb->createJoin('books', 'wb')
                    ->leftJoin('prices', 'wbp')))
            ->leftJoin('prices', 'p')
            ->leftJoin('inventories', 'i');

        return $qb;
    }
}
