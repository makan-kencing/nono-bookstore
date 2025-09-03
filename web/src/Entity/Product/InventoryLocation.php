<?php

declare(strict_types=1);

namespace App\Entity\Product;

enum InventoryLocation: int
{
    case STORE = 1;
    case WAREHOUSE = 2;
    case OFFSITE = 3;

    public function getEstimatedShipping(): string
    {
        return match ($this) {
            InventoryLocation::STORE => 'Ship within 1-2 days',
            InventoryLocation::WAREHOUSE => 'Ship within 6-7 days',
            InventoryLocation::OFFSITE => 'Ship within 2 weeks'
        };
    }

    public function compareTo(InventoryLocation $other): int
    {
        return $this->value - $other->value;
    }
}
