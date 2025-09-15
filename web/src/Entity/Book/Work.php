<?php

declare(strict_types=1);

namespace App\Entity\Book;

use function App\Utils\array_find;
use App\Entity\Book\Author\Author;
use App\Entity\Book\Category\Category;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Book\Series\SeriesDefinition;
use App\Entity\Rating\Rating;
use App\Entity\Trait\Sluggable;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class Work extends Entity
{
    use Sluggable;

    #[Id]
    public ?int $id;

    public string $slug;

    public string $title;

    #[ManyToOne]
    public ?Author $author;

    #[OneToOne]
    public ?Book $defaultEdition;

    #[OneToOne(mappedBy: 'work')]
    public ?SeriesDefinition $series;

    /** @var Book[] */
    #[OneToMany(Book::class, mappedBy: 'work')]
    public array $books;

    /** @var CategoryDefinition[] */
    #[OneToMany(CategoryDefinition::class, mappedBy: 'work')]
    public array $categories;

    /** @var Rating[] */
    #[OneToMany(Rating::class, mappedBy: 'work')]
    public array $ratings;

    public function getPrimaryCategory(): ?CategoryDefinition {
        return array_find(
            $this->categories,
            fn (CategoryDefinition $category) => $category->isPrimary
        ) ?? current($this->categories) ?: null;
    }
}
