<?php

namespace App\Entity\Book\Author;

use App\Entity\Book\Book;
use App\Entity\Trait\Commentable;
use App\Orm\Entity;
use App\Orm\ManyToOne;

class AuthorDefinition extends Entity
{
    use Commentable;

    #[ManyToOne]
    public Book $book;

    #[ManyToOne]
    public Author $author;

    public AuthorDefinitionType $type;
}
