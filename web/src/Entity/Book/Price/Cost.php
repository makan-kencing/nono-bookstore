<?php

namespace App\Entity\Book\Price;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\TimeLimited;

class Cost extends Entity
{
    use TimeLimited;

    private int $amount {
        get => $this->amount;
        set => $this->amount;
    }
}