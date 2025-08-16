<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class Inventory extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Product $product;

    public InventoryLocation $location;

    public int $quantity;
}
