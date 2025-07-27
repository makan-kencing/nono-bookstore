<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Series\Series;
use PDOStatement;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<Series>
 */
readonly class SeriesRowMapper extends RowMapper
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
    public function mapRow(array $row, string $prefix = ''): Series
    {
        $id = $row[$prefix . 'id'];
        $series = new Series();
        $series->id = $id;
        try {
            $series->slug = $row[$prefix . 'slug'];
            $series->name = $row[$prefix . 'name'];
            $series->description = $row[$prefix . 'description'];
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $series = new Series();
            $series->id = $id;
            $series->isLazy = true;
        }
        return $series;
    }
}
