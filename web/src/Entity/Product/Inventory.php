<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\Entity;

class Inventory extends Entity
{
    public Product $product;
    public int $quantity;
}
