<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Book\Book;
use App\Entity\Book\Work;
use App\Entity\User\User;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class WishlistItem extends Entity
{
    #[Id]
    #[ManyToOne]
    public User $user;

    #[Id]
    #[ManyToOne]
    public Book $book;
}
