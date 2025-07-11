<?php

namespace App\Entity;

enum PaymentMethod
{
    case CASH_ON_DELIVERY;
    case VISA;
    case MASTERCARD;
    case AMEX;
}
