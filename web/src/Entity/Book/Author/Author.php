<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Sluggable;

class Author extends IdentifiableEntity
{
    use Sluggable;

    public string $name;
    public ?string $description;
    /** @var AuthorDefinition[] */
    public array $books;
}
