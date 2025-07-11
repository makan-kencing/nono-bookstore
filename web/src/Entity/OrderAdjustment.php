<?php

namespace App\Entity;

use App\Entity\Trait\Commetable;

abstract class OrderAdjustment
{
    use Commetable;

    private PriceType $type {
        get => $this->type;
        set => $this->type;
    }
}