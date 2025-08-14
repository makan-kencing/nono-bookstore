<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Entity\Trait\Sluggable;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToMany;

class Author extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;

    public ?string $description;

    /** @var AuthorDefinition[] */
    #[OneToMany(AuthorDefinition::class, mappedBy: 'author', optional: true)]
    public array $books;
}
