<?php

declare(strict_types=1);

namespace App\Entity\Product\Price\Constraint;

use App\Entity\Product\Product;

class ProductConstraint extends Constraint
{
    public Product $product;
}
