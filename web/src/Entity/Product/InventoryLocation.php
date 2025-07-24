<?php

declare(strict_types=1);

namespace App\Entity\Product;

enum InventoryLocation
{
    case STORE;
    case WAREHOUSE;
    case OFFSITE;
}
