<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Trait\Commetable;
use App\Entity\Cart\CartItem;

class OrderItem extends CartItem
{
    use Commetable;

    public int $unitPrice;
    /**
     * @var OrderAdjustment[]
     */
    public ?array $adjustments;
}
