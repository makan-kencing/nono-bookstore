<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;

class Inventory extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Product $product;

    public InventoryLocation $location;

    public int $quantity;
}
