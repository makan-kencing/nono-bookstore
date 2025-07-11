<?php

namespace App\Entity\Book\Author;

use App\Entity\ABC\Trait\Commetable;

class AuthorDefinition
{
    use Commetable;

    private Author $author {
        get => $this->author;
        set => $this->author;
    }

    private AuthorDefinitionType $type {
        get => $this->type;
        set => $this->type;
    }
}