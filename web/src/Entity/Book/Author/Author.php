<?php

namespace App\Entity\Book\Author;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;

class Author extends Entity
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
}