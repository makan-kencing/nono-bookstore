<?php

declare(strict_types=1);

namespace App\Entity\Book\Author;

use App\Utils\EnumUtils;

enum AuthorDefinitionType: int
{
    use EnumUtils;

    case AUTHOR = 1;
    case ILLUSTRATOR = 2;
    case EDITOR = 3;
    case CONTRIBUTOR = 4;
    case TRANSLATOR = 5;
}
