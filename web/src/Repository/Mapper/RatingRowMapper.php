<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Rating;
use PDOStatement;
use DateTime;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<Rating>
 */
readonly class RatingRowMapper extends RowMapper
{
    public const string REPLIES = 'replies.';

    private UserRowMapper $userRowMapper;

    public function __construct()
    {
        $this->userRowMapper = new UserRowMapper();
    }

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
    public function mapRow(array $row, string $prefix = ''): Rating
    {
        $id = $row[$prefix . 'id'];
        $rating = new Rating();
        $rating->id = $id;
        try {
            $rating->user = $this->userRowMapper->mapRow($row, prefix: $prefix . 'user.');
            $rating->rating = $row[$prefix . 'rating'];
            $rating->title = $row[$prefix . 'title'];
            $rating->content = $row[$prefix . 'content'];
            $rating->ratedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'ratedAt']);
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $rating = new Rating();
            $rating->id = $id;
            $rating->isLazy = true;
        }
        return $rating;
    }
}
