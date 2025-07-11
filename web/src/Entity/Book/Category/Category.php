<?php

namespace App\Entity\Book\Category;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;

class Category extends Entity
{
    use Sluggable;

    private string $name {
        get => $this->name;
        set => $this->name;
    }

    private string $description {
        get => $this->description;
        set => $this->description;
    }

    /**
     * @var Category[]
     */
    private array $subcategories = [] {
        get => $this->subcategories;
    }
}