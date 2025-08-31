<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;
use DateTime;

class Payment extends Entity
{
    #[Id]
    public ?int $id;

    #[OneToOne]
    public Invoice $invoice;

    public string $refNo;

    public PaymentMethod $method;

    public int $amount;

    public DateTime $paidAt;
}
