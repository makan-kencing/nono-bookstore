<?php

namespace App\Entity\Constraint;

class BookConstraint extends Constraint
{
    private int $book_id {
        get => $this->book_id;
        set => $this->book_id;
    }
}