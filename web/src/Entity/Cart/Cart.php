<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\User\User;
use App\Orm\Attribute\Id;
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
}
