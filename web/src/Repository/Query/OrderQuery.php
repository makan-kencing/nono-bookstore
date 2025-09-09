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
            ->leftJoin('adjustments', 'adj')
            ->join('user', 'u')
            ->leftJoin('shipment', 's')
            ->join($qb->createJoin('items', 'it')
                ->leftJoin($qb->createJoin('book', 'b')
                    ->leftJoin($qb->createJoin('images', 'bi')
                        ->leftJoin('file', 'f'))));
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
