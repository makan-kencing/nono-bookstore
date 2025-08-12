<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use App\Entity\ABC\IdentifiableEntity;
use DateTime;

class Payment extends IdentifiableEntity
{
    public Invoice $invoice;
    public string $refNo;
    public PaymentMethod $method;
    public int $amount;
    public DateTime $paidAt;
}
