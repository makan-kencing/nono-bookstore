<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\ABC\Entity;
use App\Entity\Product\Product;

class CartItem extends Entity
{
    public Product $product;
    public int $quantity;
}
