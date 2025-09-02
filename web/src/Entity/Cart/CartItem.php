<?php

declare(strict_types=1);

namespace App\Entity\Cart;

use App\Entity\Product\Product;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class CartItem extends Entity
{
    #[Id]
    #[ManyToOne]
    public Cart $cart;

    #[Id]
    #[ManyToOne]
    public Product $product;

    public int $quantity;
}
