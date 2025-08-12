<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\IdentifiableEntity;

class Inventory extends IdentifiableEntity
{
    public Product $product;
    public InventoryLocation $location;
    public int $quantity;
}
