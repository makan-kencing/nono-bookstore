<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Product\Product;
use App\Entity\User\User;
use App\Orm\Entity;
use App\Orm\ManyToOne;

class WishlistItem extends Entity
{
    #[ManyToOne]
    public User $user;

    #[ManyToOne]
    public Product $items;
}
