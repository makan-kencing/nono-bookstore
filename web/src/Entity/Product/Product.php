<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\TimeLimited;
use App\Entity\Book\Book;

abstract class Product extends IdentifiableEntity
{
    use TimeLimited;

    public Book $book;
    public CoverType $coverType;
    /** @var Cost[] */
    public array $cost;
    /** @var Price[] */
    public array $prices;
    /** @var Inventory[] */
    public array $inventories;
}
