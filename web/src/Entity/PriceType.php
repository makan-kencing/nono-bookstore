<?php

namespace App\Entity;

enum PriceType
{
    case DISCOUNT;
    case SURCHARGE;
    case TAX;
    case SHIPPING;
}
