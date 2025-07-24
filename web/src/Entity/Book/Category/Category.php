<?php

declare(strict_types=1);

namespace App\Entity\Book\Category;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;

class Category extends Entity
{
    use Sluggable;

    public ?int $id;
    public string $name;
    public ?string $description;
    public ?Category $parent;
    /**
     * @var Category[]
     */
    public ?array $subcategories;
}
