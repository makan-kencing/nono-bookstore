<?php

namespace App\Entity;

use App\Entity\Trait\Commetable;

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