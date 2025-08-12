<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\AssociativeEntity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinitionType;

class SeriesAuthorDefinition extends AssociativeEntity
{
    use Commentable;

    public Series $series;
    public Author $author;
    public AuthorDefinitionType $type;
}
