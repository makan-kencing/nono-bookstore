<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use function App\Utils\array_any;
use App\Entity\Order\Order;
use App\Entity\Order\OrderAdjustment;
use App\Entity\Order\OrderAdjustmentType;
use App\Entity\User\Address;
use App\Entity\User\User;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class Cart extends Entity
{
    #[Id]
    public ?int $id;

    #[OneToOne]
    public ?User $user;

    /** @var CartItem[] */
    #[OneToMany(CartItem::class, mappedBy: 'cart')]
    public array $items;

    #[ManyToOne]
    public ?Address $address;

    public function getSubtotal(): int
    {
        return array_reduce(
                $this->items,
                fn(int $carry, CartItem $item) => $carry + $item->getSubtotal(),
                0
            );
    }

    public function getShipping(): int
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal > 10000) // 100 dollans
            return 0;
        return 800;
    }

    public function getTotal(): int
    {
        return $this->getSubtotal() + $this->getShipping();
    }

    public function canCheckout(): bool
    {
        return !array_any(
            $this->items,
            fn(CartItem $item) => $item->book->getTotalInStock() <= 0
        );
    }


    /**
     * @param Order $order
     * @return OrderAdjustment[]
     */
    public function toOrderAdjustments(Order $order): array
    {
        $adjustments = [];

        $shipping = new OrderAdjustment();
        $shipping->order = $order;
        $shipping->type = OrderAdjustmentType::SHIPPING;
        $shipping->amount = $this->getShipping();
        $shipping->comment = null;

        $adjustments[] = $shipping;

        return $adjustments;
    }
}
