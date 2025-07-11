<?php

namespace App\Entity\Cart;

use App\Entity\Book\Book;

class CartItem
{
    private Book $item {
        get => $this->item;
        set => $this->item;
    }

    private int $quantity {
        get => $this->quantity;
        set => $this->quantity;
    }
}