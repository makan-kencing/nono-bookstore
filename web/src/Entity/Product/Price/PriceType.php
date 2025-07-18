<?php

declare(strict_types=1);

namespace App\Entity\Product\Price;

enum PriceType
{
    case BASE;
    case DISCOUNT;
    case SURCHARGE;
}
