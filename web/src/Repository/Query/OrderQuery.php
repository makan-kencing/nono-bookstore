<?php

namespace App\Repository\Query;

use App\Entity\Order\Order;
use App\Orm\QueryBuilder;

class OrderQuery
{
    /**
     * @return QueryBuilder<Order>
     */
    public static function orderListings(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Order::class, 'o')
            ->join('address', 'oa')
            ->join('user', 'u')
            ->leftJoin('adjustments', 'adj')
            ->leftJoin('shipment', 's')
            ->join($qb->createJoin('items', 'oi')
                ->leftJoin($qb->createJoin('book', 'b')
                    ->leftJoin('work', 'w')
                    ->leftJoin($qb->createJoin('images', 'bi')
                        ->leftJoin('file', 'bif'))
                    ->leftJoin($qb->createJoin('authors', 'bad')
                        ->leftJoin('author', 'ba'))
                    ->leftJoin('prices', 'p')
                    ->leftJoin('inventories', 'i')));
        return $qb;
    }

    /**
     * @return QueryBuilder<Order>
     */
    public static function withMinimalDetails(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Order::class, 'o');
        return $qb;
    }
}
