<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Series\Series;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

/**
 * @extends RowMapper<Series>
 */
class SeriesRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string NAME = 'name';
    public const string DESCRIPTION = 'description';
    public const string BOOKS = 'books.';
    public const string AUTHORS = 'authors.';

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
    public function mapRow(array $row): Series
    {
        $id = $this->getColumn($row, self::ID);
        assert(is_int($id));

        try {
            $series = new Series();
            $series->id = $id;
            $this->bindProperties($series, $row);
        } catch (OutOfBoundsException) {
            $series = new Series();
            $series->id = $id;
            $series->isLazy = true;
        }

        return $series;
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
