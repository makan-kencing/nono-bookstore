<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Book\Series\SeriesDefinition;
use App\Entity\Rating\Rating;
use DateTime;

class Book extends Entity
{
    use Sluggable;

    public ?int $id;
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
