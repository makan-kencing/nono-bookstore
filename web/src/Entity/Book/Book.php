<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Book\Series\SeriesDefinition;
use App\Entity\Cart\WishlistItem;
use App\Entity\Product\Product;
use App\Entity\Rating\Rating;
use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class Book extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $isbn;

    public string $title;

    public ?string $description;

    /** @var BookImage[] */
    #[OneToMany(BookImage::class, mappedBy: 'book')]
    public array $images;

    /** @var AuthorDefinition[] */
    #[OneToMany(AuthorDefinition::class, mappedBy: 'book')]
    public array $authors;

    /** @var CategoryDefinition[] */
    #[OneToMany(CategoryDefinition::class, mappedBy: 'book')]
    public array $categories;

    /** @var Rating[] */
    #[OneToMany(Rating::class, mappedBy: 'book')]
    public array $ratings;

    #[OneToOne]
    public Publisher $publisher;

    public string $publishedDate;

    #[OneToOne(mappedBy: 'book')]
    public ?SeriesDefinition $series;

    public int $numberOfPages;

    public string $language;

    public string $dimensions;

    /** @var Product[] */
    #[OneToMany(Product::class, mappedBy: 'book')]
    public array $products;

    /** @var WishlistItem[] */
    #[OneToMany(WishlistItem::class, mappedBy: 'book')]
    public array $wishlisted;
}
