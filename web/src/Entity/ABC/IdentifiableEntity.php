<?php

declare(strict_types=1);

namespace App\Entity\ABC;

abstract class IdentifiableEntity extends Entity
{
    public ?int $id;
}
