<?php

namespace App\Entity\Trait;

use DateTime;

trait Updatable
{
    private DateTime $updated_at {
        get => $this->updated_at;
        set => $this->updated_at;
    }
}