<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use App\Entity\ABC\IdentifiableEntity;
use DateTime;

class Invoice extends IdentifiableEntity
{
    public Order $order;
    public ?Payment $payment;
    public DateTime $invoicedAt;
}
