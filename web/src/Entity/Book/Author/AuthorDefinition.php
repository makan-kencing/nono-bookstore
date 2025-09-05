<?php

namespace App\Entity\Book\Author;

use App\Entity\Book\Book;
use App\Entity\Trait\Commentable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class AuthorDefinition extends Entity
{
    use Commentable;

    #[Id]
    #[ManyToOne]
    public Book $book;

    #[Id]
    #[ManyToOne]
    public Author $author;

    public ?AuthorDefinitionType $type;
}
