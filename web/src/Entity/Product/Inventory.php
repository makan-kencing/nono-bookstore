<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\Entity;

class Inventory extends Entity
{
    public ?int $id;
    public Product $product;
    public InventoryLocation $location;
    public int $quantity;
}
