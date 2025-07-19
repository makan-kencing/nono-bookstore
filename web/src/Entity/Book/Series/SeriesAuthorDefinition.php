<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commetable;
use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinitionType;

class SeriesAuthorDefinition extends Entity
{
    use Commetable;

    public Series $series;
    public Author $author;
    public AuthorDefinitionType $type;
}
