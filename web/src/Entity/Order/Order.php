<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Cart\Cart;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use DateTime;

class Order extends Cart
{
    public string $refNo;

    public DateTime $orderedAt;

    #[OneToOne(mappedBy: 'order')]
    public ?Invoice $invoice;

    #[OneToOne(mappedBy: 'order')]
    public ?Shipment $shipment;

    /** @var OrderItem[] */
    #[OneToMany(OrderItem::class, mappedBy: 'order')]
    public array $items;

    /** @var OrderAdjustment[] */
    #[OneToMany(OrderAdjustment::class, mappedBy: 'order', optional: true)]
    public ?array $adjustments;
}
