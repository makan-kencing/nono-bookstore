<?php

namespace App\Entity\ABC\Trait;

use DateTime;

trait Publishable
{
    private string $publisher {
        get => $this->publisher;
        set => $this->publisher;
    }

    private DateTime $published_at {
        get => $this->published_at;
        set => $this->published_at;
    }
}