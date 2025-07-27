<?php

declare(strict_types=1);

namespace App\Entity\Product\Price;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\ABC\Trait\TimeLimited;
use App\Entity\ABC\Value\Value;
use App\Entity\Product\Price\Constraint\Constraint;

class PriceDefinition extends Entity
{
    use TimeLimited;
    use Commentable;

    public ?int $id;
    public PriceType $type;
    /**
     * @var Constraint[]
     */
    public ?array $constraints;
    public Value $value;
}
