<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Sluggable;

class Publisher extends IdentifiableEntity
{
    use Sluggable;

    public ?int $id;
    public string $name;
}
