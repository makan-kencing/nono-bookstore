<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Product\Product;
use App\Orm\Entity;
use App\Orm\ManyToOne;

class CartItem extends Entity
{
    #[ManyToOne]
    public Cart $cart;

    #[ManyToOne]
    public Product $product;

    public int $quantity;
}
