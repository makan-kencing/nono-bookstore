<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Book\Book;
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
}
