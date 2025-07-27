<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\Author;
use PDOStatement;
use RuntimeException;
use OutOfBoundsException;

/**
 * @extends RowMapper<Author>
 */
class AuthorRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string NAME = 'name';
    public const string DESCRIPTION = 'description';
    public const string BOOKS = 'books.';

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt): array
    {
        // TODO: Implement map() method.
        throw new RuntimeException('Not Implemented');
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Author
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $author = new Author();
            $author->id = $id;
            $this->bindProperties($author, $row);
        } catch (OutOfBoundsException) {
            $author = new Author();
            $author->id = $id;
            $author->isLazy = true;
        }

        return $author;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->slug = $this->getColumn($row, self::SLUG);
        $object->name = $this->getColumn($row, self::NAME);
        $object->description = $this->getColumn($row, self::DESCRIPTION);
    }
}
