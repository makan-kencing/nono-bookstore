<?php

namespace App\Entity;

use App\Entity\Trait\FlatRate;

class FlatPriceDefinition extends PriceDefinition
{
    use FlatRate;
}