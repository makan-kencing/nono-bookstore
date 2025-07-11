<?php

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use DateTime;

class Payment extends Entity
{
    private string $ref_no {
        get => $this->ref_no;
        set => $this->ref_no;
    }

    private PaymentMethod $method {
        get => $this->method;
        set => $this->method;
    }

    private int $amount {
        get => $this->amount;
        set => $this->amount;
    }

    private DateTime $paid_at {
        get => $this->paid_at;
        set => $this->paid_at;
    }
}