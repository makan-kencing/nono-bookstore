<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Orm\Entity;
use DateTime;

/**
 * @phpstan-require-extends Entity
 */
trait SoftDeletable
{
    public ?DateTime $deleted_at;
}
