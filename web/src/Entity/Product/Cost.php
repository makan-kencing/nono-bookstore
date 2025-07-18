<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\TimeLimited;

class Cost extends Entity
{
    use TimeLimited;

    public ?int $id;
    public Product $product;
    public int $amount;
}
