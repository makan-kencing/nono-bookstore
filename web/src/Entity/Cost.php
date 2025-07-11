<?php

namespace App\Entity;

use App\Entity\Trait\TimeLimited;

class Cost extends Entity
{
    use TimeLimited;

    private int $amount {
        get => $this->amount;
        set => $this->amount;
    }
}