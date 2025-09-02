<?php

namespace App\Entity\Book\Series;

use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Trait\Commentable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class SeriesAuthorDefinition extends Entity
{
    use Commentable;

    #[Id]
    #[ManyToOne]
    public Series $series;

    #[Id]
    #[ManyToOne]
    public Author $author;

    public AuthorDefinitionType $type;
}
