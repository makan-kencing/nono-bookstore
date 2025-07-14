<?php

declare(strict_types=1);

namespace App\Entity\ABC;

abstract class Entity
{
    public readonly ?int $id;
    public readonly ?bool $isLazy;

    public function __construct(?int $id = null)
    {
        $this->isLazy = true;
        $this->id = $id;
    }
}
