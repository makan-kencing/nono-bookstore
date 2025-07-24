<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\Author;
use PDOStatement;

/**
 * @extends RowMapper<Author>
 */
readonly class AuthorRowMapper extends RowMapper
{
    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = '')
    {
        // TODO: Implement map() method.
    }

    /**
     * @inheritDoc
     */
    public function mapRow(mixed $row, string $prefix = '')
    {
        $author = new Author();

        $author->id = $row[$prefix . 'id'];
        $author->slug = $row[$prefix . 'slug'];
        $author->name = $row[$prefix . 'name'];
        $author->description = $row[$prefix . 'description'];

        return $author;
    }
}
