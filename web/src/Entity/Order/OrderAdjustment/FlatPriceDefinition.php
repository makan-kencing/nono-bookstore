<?php

namespace App\Entity\Order\OrderAdjustment;

use App\Entity\ABC\Trait\FlatRate;
use App\Entity\Book\Price\PriceDefinition;

class FlatPriceDefinition extends PriceDefinition
{
    use FlatRate;
}