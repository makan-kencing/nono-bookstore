<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\TimeLimited;
use App\Entity\Book\Book;
use App\Entity\Product\Price\PriceDefinition;

abstract class Product extends Entity
{
    use TimeLimited;

    public ?int $id;
    public Book $book;
    public CoverType $coverType;
    /**
     * @var PriceDefinition[]
     */
    public ?array $priceDefinitions;
    public Cost $cost;
}
