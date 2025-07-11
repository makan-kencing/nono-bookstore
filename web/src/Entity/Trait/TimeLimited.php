<?php

namespace App\Entity\Trait;

use DateTime;

trait TimeLimited
{
    private DateTime $from_date {
        get => $this->from_date;
        set => $this->from_date;
    }

    private ?DateTime $thru_date {
        get => $this->thru_date;
        set => $this->thru_date;
    }
}