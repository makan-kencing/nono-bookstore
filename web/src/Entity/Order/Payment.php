<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use DateTime;

class Payment extends Entity
{
    public ?int $id;
    public Invoice $invoice;
    public string $refNo;
    public PaymentMethod $method;
    public int $amount;
    public DateTime $paidAt;
}
