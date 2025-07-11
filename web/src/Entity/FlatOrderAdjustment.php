<?php

namespace App\Entity;

use App\Entity\Trait\FlatRate;

class FlatOrderAdjustment extends OrderAdjustment
{
    use FlatRate;
}