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
}
