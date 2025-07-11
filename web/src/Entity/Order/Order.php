<?php

namespace App\Entity\Order;

use App\Entity\Cart\Cart;
use App\Entity\Order\OrderAdjustment\OrderAdjustment;
use DateTime;

class Order extends Cart
{
    private string $ref_no {
        get => $this->ref_no;
        set => $this->ref_no;
    }

    private DateTime $ordered_at {
        get => $this->ordered_at;
        set => $this->ordered_at;
    }

    /**
     * @var OrderItem[]
     */
    private array $items = [] {
        get => $this->items;
    }

    /**
     * @var OrderAdjustment[]
     */
    private array $adjustments = [] {
        get => $this->adjustments;
    }

    private ?Invoice $invoice {
        get => $this->invoice;
        set => $this->invoice;
    }

    private ?Shipment $shipment {
        get => $this->shipment;
        set => $this->shipment;
    }
}