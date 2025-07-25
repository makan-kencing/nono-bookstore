<?php

declare(strict_types=1);

namespace App\Entity\ABC\Trait;

use App\Entity\ABC\Entity;

/**
 * @phpstan-require-extends Entity
 */
trait Sluggable
{
    public string $slug;
}
