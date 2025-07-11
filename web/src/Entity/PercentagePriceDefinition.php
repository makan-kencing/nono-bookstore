<?php

namespace App\Entity;

use App\Entity\Trait\PercentageRate;

class PercentagePriceDefinition extends PriceDefinition
{
    use PercentageRate;
}