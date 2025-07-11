<?php

namespace App\Entity\ABC\Trait;

use DateTime;

trait Updatable
{
    private DateTime $updated_at {
        get => $this->updated_at;
        set => $this->updated_at;
    }
}