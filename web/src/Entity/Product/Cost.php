<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\Book\Book;
use App\Entity\Trait\Commentable;
use App\Entity\Trait\TimeLimited;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class Cost extends Entity
{
    use TimeLimited;
    use Commentable;

    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Book $book;

    public int $amount;
}
