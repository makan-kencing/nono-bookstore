<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;

class Author extends Entity
{
    use Sluggable;

    public ?int $id;
    public string $name;
    public ?string $description;
}
