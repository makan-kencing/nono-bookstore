<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\BookImage;
use PDOStatement;
use Throwable;

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
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

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
