<?php

declare(strict_types=1);

namespace App\Entity\Product;

enum CoverType: int
{
    case PAPERBACK = 1;
    case HARDCOVER = 2;
}
