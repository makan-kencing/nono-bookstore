<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Book;

class Series extends IdentifiableEntity
{
    use Sluggable;

    public string $name;
    public ?string $description;
    /** @var Book[] */
    public array $books;
    /** @var SeriesAuthorDefinition[] */
    public array $authors;
}
