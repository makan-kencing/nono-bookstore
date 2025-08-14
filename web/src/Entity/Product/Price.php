<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\ABC\Trait\TimeLimited;

class Price extends IdentifiableEntity
{
    use TimeLimited;
    use Commentable;

    public Product $product;
    public int $amount;
}
