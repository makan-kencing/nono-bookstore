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
        /** @var QueryBuilder<Cart> $qb */
        $qb = new QueryBuilder();
        $qb->from(Cart::class, 'c')
            ->leftJoin($qb->createJoin('items', 'ci')
                ->leftJoin($qb->createJoin('book', 'b')
                    ->leftJoin('work', 'w')
                    ->leftJoin($qb->createJoin('images', 'bi')
                        ->leftJoin('file', 'bif'))
                    ->leftJoin($qb->createJoin('authors', 'bad')
                        ->leftJoin('author', 'ba'))
                    ->leftJoin('prices', 'p')
                    ->leftJoin('inventories', 'i')))
            ->join($qb->createJoin('user', 'u')
                ->leftJoin('addresses', 'ua'))
            ->leftJoin('address', 'a');
        return $qb;
    }
}
