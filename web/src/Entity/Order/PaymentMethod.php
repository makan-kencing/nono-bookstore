<?php

namespace App\Entity\Order;

enum PaymentMethod
{
    case CASH_ON_DELIVERY;
    case VISA;
    case MASTERCARD;
    case AMEX;
}
