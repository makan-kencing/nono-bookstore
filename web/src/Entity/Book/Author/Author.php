<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Entity\File;
use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class Author extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;

    public ?string $description;

    #[OneToOne]
    public File $image;

    /** @var AuthorDefinition[] */
    #[OneToMany(AuthorDefinition::class, mappedBy: 'author')]
    public array $books;
}
