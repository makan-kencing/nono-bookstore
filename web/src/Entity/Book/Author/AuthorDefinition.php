<?php

namespace App\Entity\Book\Author;

use App\Entity\ABC\AssociativeEntity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\Book\Book;

class AuthorDefinition extends AssociativeEntity
{
    use Commentable;

    public Book $book;
    public Author $author;
    public AuthorDefinitionType $type;
}
