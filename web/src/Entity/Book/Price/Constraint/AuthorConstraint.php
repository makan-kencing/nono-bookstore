<?php

namespace App\Entity\Book\Price\Constraint;

class AuthorConstraint extends Constraint
{
    private int $author_id {
        get => $this->author_id;
        set => $this->author_id;
    }
}