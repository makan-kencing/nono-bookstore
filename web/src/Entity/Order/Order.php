<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Cart\Cart;
use DateTime;

class Order extends Cart
{
    public string $refNo;
    public DateTime $orderedAt;
    /**
     * @var OrderItem[]
     */
    public ?array $items;
    /**
     * @var OrderAdjustment[]
     */
    public ?array $adjustments;
    public ?Invoice $invoice;
    public ?Shipment $shipment;
}
