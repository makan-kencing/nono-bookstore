<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Utils\EnumUtils;

enum CoverType: int
{
    use EnumUtils;

    case PAPERBACK = 1;
    case HARDCOVER = 2;
}
