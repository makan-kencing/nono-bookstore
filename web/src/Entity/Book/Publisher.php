<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;

class Publisher extends Entity
{
    use Sluggable;

    public ?int $id;
    public string $name;
}
