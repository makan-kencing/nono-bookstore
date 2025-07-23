<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Author\Author;

/**
 * @extends Repository<Author>
 */
readonly class AuthorRepository extends Repository
{
    /**
     * @inheritDoc
     */
    #[\Override] public function mapRow(mixed $row, string $prefix = ''): Author
    {
        $author = new Author();

        $author->id = $row[$prefix . 'id'];
        $author->slug = $row[$prefix . 'slug'];
        $author->name = $row[$prefix . 'name'];
        $author->description = $row[$prefix . 'description'];

        return $author;
    }
}
