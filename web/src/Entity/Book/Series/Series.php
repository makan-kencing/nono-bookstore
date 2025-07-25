<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Book;

class Series extends Entity
{
    use Sluggable;

    public ?int $id;
    public string $name;
    public ?string $description;
    /** @var Book[] */
    public ?array $books;
    /** @var SeriesAuthorDefinition[] */
    public ?array $authors;
}
