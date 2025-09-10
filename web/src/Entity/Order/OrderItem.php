<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Book\Book;
use App\Entity\Trait\Commentable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class OrderItem extends Entity
{
    use Commentable;

    #[Id]
    #[ManyToOne]
    public Order $order;

    #[Id]
    #[ManyToOne]
    public Book $book;

    public int $quantity;

    public int $unitPrice;

    public function getSubtotal(): int
    {
        return $this->unitPrice * $this->quantity;
    }
}
