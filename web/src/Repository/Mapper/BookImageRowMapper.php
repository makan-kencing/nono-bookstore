<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\BookImage;
use PDOStatement;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<BookImage>
 */
readonly class BookImageRowMapper extends RowMapper
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
    public function mapRow(array $row, string $prefix = ''): BookImage
    {
        $id = $row[$prefix . 'id'];
        $bookImage = new BookImage();
        $bookImage->id = $id;
        try {
            $bookImage->imageUrl = $row[$prefix . 'imageUrl'];
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $bookImage = new BookImage();
            $bookImage->id = $id;
            $bookImage->isLazy = true;
        }

        return $bookImage;
    }
}
