<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Trait\Commentable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class OrderAdjustment extends Entity
{
    use Commentable;

    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Order $order;

    public OrderAdjustmentType $type;

    public int $amount;
}
