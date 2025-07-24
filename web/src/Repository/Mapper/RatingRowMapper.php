<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Rating;
use PDOStatement;

readonly class RatingRowMapper extends RowMapper
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
        $userRowMapper = new UserRowMapper();

        $rating = new Rating();

        $rating->id = $row[$prefix . 'id'];
        $rating->user = $userRowMapper->mapRow($row, prefix: $prefix . 'user.');
        $rating->rating = $row[$prefix . 'rating'];
        $rating->title = $row[$prefix . 'title'];
        $rating->content = $row[$prefix . 'content'];

        return $rating;
    }
}
