<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Utils\EnumUtils;

enum AuthorDefinitionType
{
    use EnumUtils;

    case AUTHOR;
    case ILLUSTRATOR;
    case CONTRIBUTOR;
    case EDITOR;
    case TRANSLATOR;
}
