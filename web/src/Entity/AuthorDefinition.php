<?php

namespace App\Entity;

use App\Entity\Trait\Commetable;

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