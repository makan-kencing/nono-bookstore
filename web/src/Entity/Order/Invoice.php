<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use DateTime;

class Invoice extends Entity
{
    public ?int $id;
    public Order $order;
    public ?Payment $payment;
    public DateTime $invoicedAt;
}
