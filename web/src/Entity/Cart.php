<?php

namespace App\Entity;

class Cart extends Entity
{
    private User $user {
        get => $this->user;
        set => $this->user = $value;
    }

    private Address $address {
        get => $this->address;
        set => $this->address = $value;
    }

    /**
     * @var CartItem[]
     */
    private array $items = [] {
        get => $this->items;
    }
}