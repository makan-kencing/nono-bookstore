<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\File;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class BookImage extends Entity
{
    #[Id]
    #[ManyToOne]
    public Book $book;

    #[Id]
    #[ManyToOne]
    public File $file;

    public int $imageOrder;
}
