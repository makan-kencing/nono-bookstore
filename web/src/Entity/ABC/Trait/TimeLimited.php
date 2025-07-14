<?php

declare(strict_types=1);

namespace App\Entity\ABC\Trait;

use DateTime;

trait TimeLimited
{
    public DateTime $from_date;
    public ?DateTime $thru_date;
}
