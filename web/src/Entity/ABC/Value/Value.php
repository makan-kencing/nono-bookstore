<?php

declare(strict_types=1);

namespace App\Entity\ABC\Value;

abstract class Value
{
    public int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
