<?php

declare(strict_types=1);

namespace App\Entity\Book\Category;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Sluggable;

class Category extends IdentifiableEntity
{
    use Sluggable;

    public string $name;
    public ?string $description;
    public ?Category $parent;
    /** @var Category[] */
    public array $subcategories;
}
