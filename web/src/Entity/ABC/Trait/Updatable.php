<?php

declare(strict_types=1);

namespace App\Entity\ABC\Trait;

use DateTime;

trait Updatable
{
    public DateTime $updated_at;
}
