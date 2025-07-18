<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\Rating\Rating;
use DateTime;
use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Product\Cost;
use App\Entity\Product\Price\PriceDefinition;

class Book extends Entity
{
    use Sluggable;

    public ?int $id;
    public string $isbn;
    public string $title;
    public string $description;
    /**
     * @var BookImage[]
     */
    public ?array $images;
    /**
     * @var AuthorDefinition[]
     */
    public ?array $authors;
    /**
     * @var CategoryDefinition[]
     */
    public ?array $categories;
    /**
     * @var Rating[]
     */
    public ?array $ratings;
    public string $publisher;
    public DateTime $publishedAt;
    public int $numberOfPages;
    public string $language;
    public string $dimensions;
}
