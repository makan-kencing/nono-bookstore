<?php

declare(strict_types=1);

namespace App\Entity\Product\Price\Constraint;

use App\Entity\Book\Book;

class BookConstraint extends Constraint
{
    public Book $book;
}
