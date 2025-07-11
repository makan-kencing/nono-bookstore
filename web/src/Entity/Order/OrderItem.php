<?php

namespace App\Entity\Order;

use App\Entity\ABC\Trait\Commetable;
use App\Entity\Cart\CartItem;
use App\Entity\Order\OrderAdjustment\OrderAdjustment;

class OrderItem extends CartItem
{
    use Commetable;

    private int $unit_price {
        get => $this->unit_price;
        set => $this->unit_price;
    }

    /**
     * @var OrderAdjustment[]
     */
    private array $adjustments = [] {
        get => $this->adjustments;
    }
}