<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Trait\Updatable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;
use DateTime;

class Shipment extends Entity
{
    use Updatable;

    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Order $order;

    public ?DateTime $readyAt;

    public ?DateTime $shippedAt;

    public ?DateTime $arrivedAt;
}
