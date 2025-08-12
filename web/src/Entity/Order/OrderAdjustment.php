<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\ABC\Value\Value;

abstract class OrderAdjustment extends IdentifiableEntity
{
    use Commentable;

    public OrderAdjustmentType $type;
    public Value $value;
}
