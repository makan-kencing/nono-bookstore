<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Cart\Cart;
use App\Orm\QueryBuilder;

class CartQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Cart>
     */
    public static function forShoppingCart(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(Cart::class, 'c')
            ->leftJoin($qb->createJoin('items', 'i')
                ->leftJoin($qb->createJoin('book', 'b')
                    ->leftJoin('work', 'w')
                    ->leftJoin('images', 'wi')
                    ->leftJoin($qb->createJoin('authors', 'bad')
                        ->leftJoin('author', 'ba'))
                    ->leftJoin('prices', 'p')
                    ->leftJoin('inventories', 'bi')));
        return $qb;
    }
}
