<?php

namespace App\Entity\Book\Series;

use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Trait\Commentable;
use App\Orm\Entity;
use App\Orm\ManyToOne;

class SeriesAuthorDefinition extends Entity
{
    use Commentable;

    #[ManyToOne]
    public Series $series;

    #[ManyToOne]
    public Author $author;

    public AuthorDefinitionType $type;
}
