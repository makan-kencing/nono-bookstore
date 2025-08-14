<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\User\User;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToMany;
use App\Orm\OneToOne;

class Cart extends Entity
{
    #[Id]
    public ?int $id;

    #[OneToOne]
    public ?User $user;

    /** @var CartItem[] */
    #[OneToMany(CartItem::class)]
    public array $items;
}
