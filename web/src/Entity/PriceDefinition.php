<?php

namespace App\Entity;

use App\Entity\Constraint\Constraint;

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