<?php

namespace App\Entity\Book\Author;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\Book\Book;

class AuthorDefinition extends Entity
{
    use Commentable;

    public Book $book;
    public Author $author;
    public AuthorDefinitionType $type;
}
