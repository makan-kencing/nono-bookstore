<?php

declare(strict_types=1);

namespace App\Entity\ABC;

abstract class Entity
{
    public readonly ?bool $isLazy;

    public function __construct(bool $isLazy = false)
    {
        $this->isLazy =  $isLazy;
    }
}
