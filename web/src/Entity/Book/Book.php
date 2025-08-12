<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Book\Series\SeriesDefinition;
use App\Entity\Rating\Rating;

class Book extends IdentifiableEntity
{
    use Sluggable;

    public string $isbn;
    public string $title;
    public ?string $description;
    /** @var BookImage[] */
    public array $images;
    /** @var AuthorDefinition[] */
    public array $authors;
    /** @var CategoryDefinition[] */
    public array $categories;
    /** @var Rating[] */
    public array $ratings;
    public Publisher $publisher;
    public string $publishedDate;
    public int $numberOfPages;
    public ?SeriesDefinition $series;
    public string $language;
    public string $dimensions;
}
