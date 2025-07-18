<?php

namespace App\Entity\Cart;

use App\Entity\ABC\Entity;
use App\Entity\Book\Book;
use App\Entity\User\User;

class Wishlist extends Entity
{
    public User $user;
    /**
     * @var Book[]
     */
    public ?array $books;
}
