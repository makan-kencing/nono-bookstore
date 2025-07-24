<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Series\Series;
use PDOStatement;

/**
 * @extends RowMapper<Series>
 */
readonly class SeriesRowMapper extends RowMapper
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
        $series = new Series();

        $series->id = $row[$prefix . 'id'];
        $series->slug = $row[$prefix . 'slug'];
        $series->name = $row[$prefix . 'name'];
        $series->description = $row[$prefix . 'description'];

        return $series;
    }
}
