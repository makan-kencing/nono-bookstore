<?php

namespace App\Entity\Book\Price\Constraint;

class CategoryConstraint extends Constraint
{
    private int $category_id {
        get => $this->category_id;
        set => $this->category_id;
    }
}