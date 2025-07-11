<?php

namespace App\Entity;

use App\Entity\Trait\Sluggable;

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