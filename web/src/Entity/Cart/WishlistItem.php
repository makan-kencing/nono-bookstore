<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\ABC\AssociativeEntity;
use App\Entity\Product\Product;
use App\Entity\User\User;

class WishlistItem extends AssociativeEntity
{
    public User $user;
    public Product $items;
}
