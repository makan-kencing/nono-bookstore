<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\Book\Book;
use App\Entity\Cart\CartItem;
use App\Entity\Order\OrderItem;
use App\Entity\Trait\TimeLimited;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Entity;
use DateTime;

class Product extends Entity
{
    use TimeLimited;

    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Book $book;

    public CoverType $coverType;

    /** @var Cost[] */
    #[OneToMany(Cost::class, mappedBy: 'product')]
    public array $costs;

    /** @var Price[] */
    #[OneToMany(Price::class, mappedBy: 'product')]
    public array $prices;

    /** @var Inventory[] */
    #[OneToMany(Inventory::class, mappedBy: 'product')]
    public array $inventories;

    /** @var CartItem[] */
    #[OneToMany(CartItem::class, mappedBy: 'product')]
    public array $inCarts;

    /** @var OrderItem[] */
    #[OneToMany(OrderItem::class, mappedBy: 'product')]
    public array $inOrders;

    public function getCurrentPrice(): ?Price
    {
        $now = new DateTime();
        assert($now != null);

        return array_find(
            $this->prices,
            fn(Price $price) => $now->getTimestamp() > $price->fromDate->getTimestamp()
                && ($price->thruDate == null || $now->getTimestamp() < $price->thruDate->getTimestamp())
        );
    }

    public function getTotalInStock(): int
    {
        return array_reduce(
            $this->inventories,
            fn (int $carry, Inventory $inventory) => $carry + $inventory->quantity,
            0
        );
    }

    public function getClosestStock(): ?Inventory
    {
        uasort(
            $this->inventories,
            fn (Inventory $a, Inventory $b) => $a->location->compareTo($b->location)
        );
        return current($this->inventories) ?: null;
    }
}
