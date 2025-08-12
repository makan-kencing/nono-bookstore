<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\AssociativeEntity;
use App\Entity\File;

class BookImage extends AssociativeEntity
{
    public Book $book;
    public File $file;
    public int $imageOrder;
}
