<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\User\Address;
use App\Entity\User\User;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;
use DateTime;

class Order extends Entity
{
    #[Id]
    public ?int $id;

    #[OneToOne]
    public User $user;

    #[ManyToOne]
    public Address $address;

    public string $refNo;

    public DateTime $orderedAt;

    /** @var OrderItem[] */
    #[OneToMany(OrderItem::class, mappedBy: 'order')]
    public array $items;

    #[OneToOne(mappedBy: 'order')]
    public ?Invoice $invoice;

    #[OneToOne(mappedBy: 'order')]
    public ?Shipment $shipment;

    /** @var OrderAdjustment[] */
    #[OneToMany(OrderAdjustment::class, mappedBy: 'order')]
    public array $adjustments;

    public function getSubtotal(): int
    {
        return array_reduce(
            $this->items,
            fn(int $carry, OrderItem $item) => $carry + $item->getSubtotal(),
            0
        );
    }

    public function getShipping(): int
    {
        return array_reduce(
            $this->adjustments,
            fn(int $carry, OrderAdjustment $adjustment) => $carry + ($adjustment->type === OrderAdjustmentType::SHIPPING
                ? $adjustment->amount
                : 0),
            0
        );
    }

    public function getTotal(): int
    {
        return $this->getSubtotal() + $this->getShipping();
    }

    public function getOrderStatus(): OrderStatus
    {
        if ($this->shipment === null)
            return OrderStatus::NO_SHIPMENT;

        return match (true) {
            $this->shipment->arrivedAt !== null => OrderStatus::ARRIVED,
            $this->shipment->shippedAt !== null => OrderStatus::SHIPPED,
            $this->shipment->readyAt !== null => OrderStatus::READY,
            default => OrderStatus::PREPARING,
        };
    }
}
