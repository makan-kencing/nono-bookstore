<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Rating\Rating;
use App\Orm\QueryBuilder;

class RatingQuery
{
    private function __construct() {}

    /**
     * @return QueryBuilder<Rating>
     */
    public static function withFullDetails() : QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Rating::class, 'r')
            ->join('user', 'ru')
            ->leftJoin($qb->createJoin('replies', 'rr')
                ->leftJoin('user', 'rru'))
            ->where('r.book_id = :book_id');

        return $qb;
    }
}
