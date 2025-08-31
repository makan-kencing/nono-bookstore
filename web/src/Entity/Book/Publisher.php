<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Entity;

class Publisher extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;
}
