<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\Trait\Sluggable;
use App\Orm\Entity;
use App\Orm\Id;

class Publisher extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;
}
