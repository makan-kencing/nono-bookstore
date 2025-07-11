<?php

namespace App\Entity;

use App\Entity\Trait\Updatable;
use DateTime;

class Shipment extends Entity
{
    use Updatable;

    private ?DateTime $ready_at {
        get => $this->ready_at;
        set => $this->ready_at;
    }

    private ?DateTime $shipped_at {
        get => $this->shipped_at;
        set => $this->shipped_at;
    }

    private ?DateTime $arrived_at {
        get => $this->arrived_at;
        set => $this->arrived_at;
    }
}