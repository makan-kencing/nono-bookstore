<?php

namespace App\Entity\Book\Series;

use App\Entity\Trait\Sluggable;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToMany;

class Series extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $name;

    public ?string $description;

    /** @var SeriesDefinition[] */
    #[OneToMany(SeriesDefinition::class, mappedBy: 'series', optional: true)]
    public array $books;

    /** @var SeriesAuthorDefinition[] */
    #[OneToMany(SeriesAuthorDefinition::class, mappedBy: 'author', optional: true)]
    public array $authors;
}
