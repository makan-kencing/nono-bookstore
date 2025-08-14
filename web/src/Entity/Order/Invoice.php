<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToOne;
use DateTime;

class Invoice extends Entity
{
    #[Id]
    public ?int $id;

    #[OneToOne]
    public Order $order;

    #[OneToOne(mappedBy: 'invoice')]
    public ?Payment $payment;

    public DateTime $invoicedAt;
}
