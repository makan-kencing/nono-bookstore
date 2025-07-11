<?php

namespace App\Entity\Order\OrderAdjustment;

use App\Entity\ABC\Trait\Commetable;
use App\Entity\Book\Price\PriceType;

abstract class OrderAdjustment
{
    use Commetable;

    private PriceType $type {
        get => $this->type;
        set => $this->type;
    }
}