<?php

namespace App\Entity\Book\Price;

use App\Entity\ABC\Trait\PercentageRate;

class PercentagePriceDefinition extends PriceDefinition
{
    use PercentageRate;
}