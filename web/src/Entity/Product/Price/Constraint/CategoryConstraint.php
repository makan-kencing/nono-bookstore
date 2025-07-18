<?php

declare(strict_types=1);

namespace App\Entity\Product\Price\Constraint;

use App\Entity\Book\Category\Category;

class CategoryConstraint extends Constraint
{
    public Category $category;
}
