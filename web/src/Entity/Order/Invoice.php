<?php

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use DateTime;

class Invoice extends Entity
{
    private ?Payment $payment {
        get => $this->payment;
        set => $this->payment = $value;
    }

    private DateTime $invoiced_at {
        get => $this->invoiced_at;
        set => $this->invoiced_at;
    }
}