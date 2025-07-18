<?php

declare(strict_types=1);

namespace App\Entity\Product\Price\Constraint;

use App\Entity\Book\Author\Author;

class AuthorConstraint extends Constraint
{
    public Author $author;
}
