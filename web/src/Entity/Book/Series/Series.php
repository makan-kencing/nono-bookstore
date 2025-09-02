<?php

namespace App\Entity\Book\Series;

use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToMany;
use App\Orm\Entity;

class Series extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;

    public ?string $description;

    /** @var SeriesDefinition[] */
    #[OneToMany(SeriesDefinition::class, mappedBy: 'series')]
    public array $books;

    /** @var SeriesAuthorDefinition[] */
    #[OneToMany(SeriesAuthorDefinition::class, mappedBy: 'series')]
    public array $authors;
}
