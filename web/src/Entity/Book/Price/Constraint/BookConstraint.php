<?php

namespace App\Entity\Book\Price\Constraint;

class BookConstraint extends Constraint
{
    private int $book_id {
        get => $this->book_id;
        set => $this->book_id;
    }
}