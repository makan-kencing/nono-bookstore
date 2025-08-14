<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Orm\Entity;
use DateTime;

/**
 * @phpstan-require-extends Entity
 */
trait TimeLimited
{
    public DateTime $fromDate;
    public ?DateTime $thruDate;
}
