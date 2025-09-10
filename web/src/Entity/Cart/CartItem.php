<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Book\Book;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class CartItem extends Entity
{
    #[Id]
    #[ManyToOne]
    public Cart $cart;

    #[Id]
    #[ManyToOne]
    public Book $book;

    public int $quantity;

    public function getSubtotal(): int
    {
        return $this->book->getCurrentPrice()?->amount * $this->quantity;
    }

    public function toOrderItem(Order $order): OrderItem {
        assert($this->book->getCurrentPrice() !== null);

        $item = new OrderItem();
        $item->order = $order;
        $item->book = $this->book;
        $item->quantity = $this->quantity;
        $item->unitPrice = $this->book->getCurrentPrice()->amount;
        $item->comment = null;

        return $item;
    }
}
