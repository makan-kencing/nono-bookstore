<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\User\User;

class Cart extends IdentifiableEntity
{
    public ?int $id;
    public ?User $user;
    /** @var CartItem[] */
    public array $items;
}
