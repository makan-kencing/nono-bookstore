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
    #[OneToMany(CartItem::class)]
    public array $items;
}
