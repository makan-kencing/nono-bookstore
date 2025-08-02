<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\BookImage;

/**
 * @extends RowMapper<BookImage>
 */
class BookImageRowMapper extends RowMapper
{
    public const string ID = self::FILE . FileRowMapper::ID;
    public const string IMAGE_ORDER = 'imageOrder';
    public const string FILE = 'file.';
    public const string BOOK = 'book.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): BookImage
    {
        $bookImage = new BookImage();

        $this->bindProperties($bookImage, $row);

        return $bookImage;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->imageOrder = $this->getColumn($row, self::IMAGE_ORDER);
        if ($v = $this->useMapper(BookRowMapper::class, self::BOOK)->mapRowOrNull($row)) {
            $object->book = $v;
        }
        if ($v = $this->useMapper(FileRowMapper::class, self::FILE)->mapRowOrNull($row)) {
            $object->file = $v;
        }
    }
}
