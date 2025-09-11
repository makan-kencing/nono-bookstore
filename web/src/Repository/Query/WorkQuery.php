<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Work;
use App\Orm\QueryBuilder;

class WorkQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Work>
     */
    public static function minimal(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Work::class, 'w');
        return $qb;
    }
}
