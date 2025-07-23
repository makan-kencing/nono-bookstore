<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Series\Series;

/**
 * @extends Repository<Series>
 */
readonly class SeriesRepository extends Repository
{
    /**
     * @inheritDoc
     */
    #[\Override] public function mapRow(mixed $row, string $prefix = ''): Series
    {
        $series = new Series();

        $series->id = $row[$prefix . 'id'];
        $series->slug = $row[$prefix . 'slug'];
        $series->name = $row[$prefix . 'name'];
        $series->description = $row[$prefix . 'description'];

        return $series;
    }
}
