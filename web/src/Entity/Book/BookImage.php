<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\File;
use App\Orm\Entity;
use App\Orm\ManyToOne;

class BookImage extends Entity
{
    #[ManyToOne]
    public Book $book;

    #[ManyToOne]
    public File $file;

    public int $imageOrder;
}
