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
                ->join($qb->createJoin('book', 'b')
                    ->join('work', 'w')
                    ->leftJoin('images', 'wi')
                    ->join($qb->createJoin('authors', 'bad')
                        ->join('author', 'ba'))
                    ->join('prices', 'p')
                    ->join('inventories', 'bi')));
        return $qb;
    }
}
