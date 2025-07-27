<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\BookImage;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

/**
 * @extends RowMapper<BookImage>
 */
class BookImageRowMapper extends RowMapper
{
    public const string IMAGE_URL = 'imageUrl';
    public const string BOOK = 'book.';

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
    public function mapRow(array $row): BookImage
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $bookImage = new BookImage();
            $bookImage->id = $id;
            $this->bindProperties($bookImage, $row);
        } catch (OutOfBoundsException) {
            $bookImage = new BookImage();
            $bookImage->id = $id;
            $bookImage->isLazy = true;
        }

        return $bookImage;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->imageUrl = $this->getColumn($row, self::IMAGE_URL);
    }
}
