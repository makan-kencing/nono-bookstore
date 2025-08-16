<?php

declare(strict_types=1);

namespace App\Entity\Book\Category;

use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToMany;
use App\Orm\Entity;

class Category extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;

    public ?string $description;

    /** @var CategoryDefinition[] */
    #[OneToMany(CategoryDefinition::class, mappedBy: 'category', optional: true)]
    public array $books;
}
