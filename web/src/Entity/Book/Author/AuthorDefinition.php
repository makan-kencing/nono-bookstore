<?php

namespace App\Entity\Book\Author;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commetable;
use App\Entity\Book\Book;

class AuthorDefinition extends Entity
{
    use Commetable;

    public Book $book;
    public Author $author;
    public AuthorDefinitionType $type;
}
