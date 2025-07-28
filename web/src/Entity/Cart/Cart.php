<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\ABC\Entity;
use App\Entity\User\User;

class Cart extends Entity
{
    public ?int $id;
    public ?User $user;
    /** @var CartItem[] */
    public array $items;
}
