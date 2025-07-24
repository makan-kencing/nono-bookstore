<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\Author;
use PDOStatement;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<Author>
 */
readonly class AuthorRowMapper extends RowMapper
{
    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = ''): array
    {
        // TODO: Implement map() method.
        throw new RuntimeException('Not Implemented');
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row, string $prefix = ''): ?Author
    {
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

        $author = new Author();
        $author->id = $id;
        try {
            $author->slug = $row[$prefix . 'slug'];
            $author->name = $row[$prefix . 'name'];
            $author->description = $row[$prefix . 'description'];
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $author = new Author();
            $author->id = $id;
            $author->isLazy = true;
        }

        return $author;
    }
}
