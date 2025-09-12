<?php

namespace App\Repository\Query;

use App\Entity\User\Address;
use App\Orm\QueryBuilder;

class AddressQuery
{
    private function __construct()
    {
    }

    /**
     * @return QueryBuilder<Address>
     */
    public static function minimal(): QueryBuilder
    {
        $qb = new QueryBuilder();
        return $qb->from(Address::class, 'a');
    }

}
