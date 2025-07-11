<?php

namespace App\Entity\Order\OrderAdjustment;

use App\Entity\ABC\Trait\FlatRate;

class FlatOrderAdjustment extends OrderAdjustment
{
    use FlatRate;
}