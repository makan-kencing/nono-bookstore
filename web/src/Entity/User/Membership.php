<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\TimeLimited;

class Membership extends Entity
{
    use TimeLimited;

    public string $cardNo;
}
