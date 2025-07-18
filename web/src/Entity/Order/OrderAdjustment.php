<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commetable;
use App\Entity\ABC\Value\Value;

abstract class OrderAdjustment extends Entity
{
    use Commetable;

    public ?int $id;
    public OrderAdjustmentType $type;
    public Value $value;
}
