<?php

namespace App\Entity\Book\Price;

enum PriceType
{
    case DISCOUNT;
    case SURCHARGE;
    case TAX;
    case SHIPPING;
}
