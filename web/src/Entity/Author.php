<?php

namespace App\Entity;

use App\Entity\Trait\Sluggable;

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