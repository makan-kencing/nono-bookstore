<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

enum AuthorDefinitionType: string
{
    case AUTHOR = 'Author';
    case ILLUSTRATOR = 'Illustrator';
    case CONTRIBUTOR = 'Contributor';
}
