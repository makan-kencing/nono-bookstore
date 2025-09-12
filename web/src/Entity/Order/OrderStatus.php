<?php

namespace App\Entity\Order;

use App\Utils\EnumUtils;

enum OrderStatus: int
{
    use EnumUtils;

    case NO_SHIPMENT = 0;
    case PREPARING = 1;
    case READY = 2;
    case SHIPPED = 3;
    case ARRIVED = 4;

    public function toDescription(): string
    {
        return match ($this) {
            self::NO_SHIPMENT => 'No Shipment',
            self::PREPARING => 'Preparing',
            self::READY => 'Ready To Ship',
            self::SHIPPED => 'Shipped Out',
            self::ARRIVED => 'Delivered'
        };
    }

    public function getNextStatus(): ?OrderStatus
    {
        return match ($this) {
            self::PREPARING => self::READY,
            self::READY => self::SHIPPED,
            self::SHIPPED => self::ARRIVED,
            default => null,
        };
    }
}
