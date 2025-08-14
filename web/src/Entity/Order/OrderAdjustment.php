<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Trait\Commentable;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;

abstract class OrderAdjustment extends Entity
{
    use Commentable;

    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Order $order;

    public OrderAdjustmentType $type;

    public int $amount;
}
