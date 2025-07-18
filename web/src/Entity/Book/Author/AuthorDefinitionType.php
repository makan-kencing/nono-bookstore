<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

enum AuthorDefinitionType
{
    case AUTHOR;
    case ILLUSTRATOR;
    case CONTRIBUTOR;
}
