<?php

namespace App\Entity\Book\Price;

use App\Entity\ABC\Entity;
use App\Entity\Book\Price\Constraint\Constraint;

class PriceDefinition extends Entity
{
    private PriceType $type {
        get => $this->type;
        set => $this->type;
    }

    /**
     * @var Constraint[]
     */
    private array $constraints = [] {
        get => $this->constraints;
    }
}