<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\Entity;

class BookImage extends Entity
{
    public ?int $id;
    public Book $book;
    public string $imageUrl;
}
