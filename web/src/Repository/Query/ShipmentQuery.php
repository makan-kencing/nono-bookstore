<?php

namespace App\Repository\Query;

use App\Entity\Order\Order;
use App\Entity\Order\Shipment;
use App\Orm\QueryBuilder;
class ShipmentQuery
{
    /**
     * @return QueryBuilder<Shipment>
     */
    public static  function shipmentListing():QueryBuilder{
        $qb=new QueryBuilder();
        $qb->from('Shipment','Shipment');
        return $qb;
    }

}
