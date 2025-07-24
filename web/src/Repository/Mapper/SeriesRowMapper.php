<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Series\Series;
use PDOStatement;
use Throwable;

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
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

        $series = new Series();
        $series->id = $id;
        try {
            $series->slug = $row[$prefix . 'slug'];
            $series->name = $row[$prefix . 'name'];
            $series->description = $row[$prefix . 'description'];
        } catch (Throwable $e) {
            if (!str_contains($e->getMessage(), 'Undefined array key')) {
                throw $e;
            }

            $series = new Series();
            $series->id = $id;
            $series->isLazy = true;
        }
        return $series;
    }
}
