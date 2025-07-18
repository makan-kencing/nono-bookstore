<?php

declare(strict_types=1);

namespace App\Entity\Order;

enum OrderAdjustmentType
{
    case TAX;
    case DELIVERY;
    case DISCOUNT;
    case SURCHARGE;
}
