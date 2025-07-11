<?php

namespace App\Entity\Constraint;

class CategoryConstraint extends Constraint
{
    private int $category_id {
        get => $this->category_id;
        set => $this->category_id;
    }
}