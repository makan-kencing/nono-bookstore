<?php

namespace App\Entity;

use App\Entity\Trait\PercentageRate;

class PercentageOrderAdjustment extends OrderAdjustment
{
    use PercentageRate;
}