<?php

namespace App\Entity\Order\OrderAdjustment;

use App\Entity\ABC\Trait\PercentageRate;

class PercentageOrderAdjustment extends OrderAdjustment
{
    use PercentageRate;
}