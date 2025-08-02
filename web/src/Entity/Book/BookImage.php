<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\Entity;
use App\Entity\File;

class BookImage extends Entity
{
    public Book $book;
    public File $file;
    public int $imageOrder;
}
