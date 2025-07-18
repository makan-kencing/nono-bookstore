<?php

declare(strict_types=1);

namespace App\Entity\Product\Price\Constraint;

use App\Entity\ABC\Trait\TimeLimited;

class TimeConstraint extends Constraint
{
    use TimeLimited;
}
