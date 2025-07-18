<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Updatable;
use DateTime;

class Shipment extends Entity
{
    use Updatable;

    public Order $order;
    public ?DateTime $readyAt;
    public ?DateTime $shippedAt;
    public ?DateTime $arrivedAt;
}
