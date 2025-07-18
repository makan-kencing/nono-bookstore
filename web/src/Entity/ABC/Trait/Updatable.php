<?php

declare(strict_types=1);

namespace App\Entity\ABC\Trait;

use App\Entity\ABC\Entity;
use DateTime;

/**
 * @phpstan-require-extends Entity
 */
trait Updatable
{
    public DateTime $updated_at;
}
