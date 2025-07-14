<?php

declare(strict_types=1);

namespace App\Entity\ABC;

abstract class Entity
{
    public readonly ?int $id;

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }
}
