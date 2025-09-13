<?php

namespace App\Repository\Query;

use App\Entity\Book\Category\Category;
use App\Orm\QueryBuilder;

class CategoryQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Category>
     */
    public static function minimal(): QueryBuilder
    {
        $qb =new QueryBuilder();
        return $qb->from(Category::class,'c');
    }
}
