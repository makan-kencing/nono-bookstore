<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Commentable;

abstract class OrderAdjustment extends IdentifiableEntity
{
    use Commentable;

    public OrderAdjustmentType $type;
    public int $amount;
    public Order $order;
}
