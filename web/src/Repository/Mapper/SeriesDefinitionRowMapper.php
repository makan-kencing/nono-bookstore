<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Series\SeriesDefinition;

/**
 * @extends RowMapper<SeriesDefinition>
 */
class SeriesDefinitionRowMapper extends RowMapper
{
    public const string ID = self::SERIES . SeriesRowMapper::ID;
    public const string POSITION = 'position';
    public const string SERIES_ORDER = 'series_order';
    public const string SERIES = 'series.';
    public const string BOOK = 'book.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): mixed
    {
        $seriesDefinition = new SeriesDefinition();

        $this->bindProperties($seriesDefinition, $row);

        return $seriesDefinition;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->position = $this->getColumn($row, self::POSITION);
        $object->seriesOrder = $this->getColumn($row, self::SERIES_ORDER);
        if ($v = $this->useMapper(BookRowMapper::class, self::BOOK)->mapRowOrNull($row)) {
            $object->book = $v;
        }
        if ($v = $this->useMapper(SeriesRowMapper::class, self::SERIES)->mapRowOrNull($row)) {
            $object->series = $v;
        }
    }
}
