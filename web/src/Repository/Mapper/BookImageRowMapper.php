<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\BookImage;
use PDOStatement;

/**
 * @extends RowMapper<BookImage>
 */
readonly class BookImageRowMapper extends RowMapper
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
        $bookImage = new BookImage();

        $bookImage->id = $row[$prefix . 'id'];
        $bookImage->imageUrl = $row[$prefix . 'imageUrl'];

        return $bookImage;
    }
}
