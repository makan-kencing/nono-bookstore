<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\TimeLimited;
use App\Entity\Book\Book;
use App\Entity\Product\Price\PriceDefinition;

abstract class Product extends IdentifiableEntity
{
    use TimeLimited;

    public Book $book;
    public CoverType $coverType;
    public Cost $cost;
    /** @var PriceDefinition[] */
    public array $priceDefinitions;
    /** @var Inventory[] */
    public array $inventories;
}
