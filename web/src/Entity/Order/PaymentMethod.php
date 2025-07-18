<?php

declare(strict_types=1);

namespace App\Entity\Order;

enum PaymentMethod
{
    case CASH_ON_DELIVERY;
    case VISA;
    case MASTERCARD;
    case AMEX;
}
