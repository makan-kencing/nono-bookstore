<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\User\User;
use App\Orm\QueryBuilder;

class UserQuery
{
    /**
     * @return QueryBuilder<User>
     */
    public static function withMinimalDetails(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(User::class, 'u');

        return $qb;
    }

    /**
     * @return QueryBuilder<User>
     */
    public static function withAddress(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(User::class, 'u')
            ->leftJoin('addresses', 'ad');

        return $qb;
    }

    /**
     * @return QueryBuilder<User>
     */
    public static function asProfile(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(User::class, 'u')
            ->leftJoin('profile', 'up')
            ->leftJoin('image', 'i');

        return $qb;
    }

    /**
     * @return QueryBuilder<User>
     */
    public static function userListings(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(User::class, 'u')
            ->leftJoin('profile', 'up')
            ->leftJoin('addresses', 'ad')
            ->leftJoin('image', 'i')
            ->leftJoin($qb->createJoin('orders', 'o')
                ->leftJoin('items', 'it')
                ->leftJoin("adjustments", "adj")
                ->leftJoin("shipment", "s"));
        return $qb;
    }
}
