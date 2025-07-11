<?php

namespace App\Entity\Book;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Publishable;
use App\Entity\ABC\Trait\Sluggable;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Book\Price\Cost;
use App\Entity\Book\Price\PriceDefinition;
use ValueError;

class Book extends Entity
{
    use Sluggable, Publishable;

    private string $isbn {
        get => $this->isbn;
        set {
            // trim "-"
            $value = str_replace('-', '', $value);
            if (!preg_match('/\d{13}/', $value))
                throw new ValueError($value . ' is not a isbn.');

            $this->isbn = $value;
        }
    }

    private string $title {
        get => $this->title;
        set => $this->title;
    }

    private string $description {
        get => $this->description;
        set => $this->description;
    }

    /**
     * @var string[]
     */
    private array $image_urls = [] {
        get => $this->image_urls;
    }

    /**
     * @var AuthorDefinition[]
     */
    private array $authors = [] {
        get => $this->authors;
    }

    /**
     * @var CategoryDefinition[]
     */
    private array $categories = [] {
        get => $this->categories;
    }

    private string $language {
        get => $this->language;
        set => $this->language;
    }

    private int $number_of_pages {
        get => $this->number_of_pages;
        set => $this->number_of_pages;
    }

    private BookCoverType $cover_type {
        get => $this->cover_type;
        set => $this->cover_type;
    }

    private Cost $cost {
        get => $this->cost;
        set => $this->cost;
    }

    /**
     * @var PriceDefinition[]
     */
    private array $prices = [] {
        get => $this->prices;
    }
}