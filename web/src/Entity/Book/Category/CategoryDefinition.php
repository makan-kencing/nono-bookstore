<?php

declare(strict_types=1);

namespace App\Entity\Book\Category;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\ABC\Trait\TimeLimited;
use App\Entity\Book\Book;

class CategoryDefinition extends Entity
{
    use Commentable;
    use TimeLimited;

    public Book $book;
    public Category $category;
    public bool $isPrimary = false;
}
