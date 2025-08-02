<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Rating;
use App\Entity\Rating\Reply;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<Rating>
 */
class RatingRowMapper extends RowMapper
{
    public const string RATING = 'rating';
    public const string TITLE = 'title';
    public const string CONTENT = 'content';
    public const string RATED_AT = 'ratedAt';
    public const string REPLIES = 'replies.';
    public const string USER = 'user.';
    public const string BOOK = 'book.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Rating
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $rating = new Rating();
            $rating->id = $id;
            $this->bindProperties($rating, $row);
        } catch (OutOfBoundsException) {
            $rating = new Rating();
            $rating->id = $id;
            $rating->isLazy = true;
        }
        return $rating;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->rating = $this->getColumn($row, self::RATING);
        $object->title = $this->getColumn($row, self::TITLE);
        $object->content = $this->getColumn($row, self::CONTENT);
        $object->ratedAt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::RATED_AT)
        );
        if ($v = $this->useMapper(UserRowMapper::class, self::USER)->mapRowOrNull($row)) {
            $object->user = $v;
        }
        if ($v = $this->useMapper(BookRowMapper::class, self::BOOK)->mapRowOrNull($row)) {
            $object->book = $v;
        }
    }

    /**
     * @inheritDoc
     */
    public function bindOneToManyProperties(mixed $object, array $row): void
    {
        $object->replies ??= [];
        $this->useMapper(ReplyRowMapper::class, self::REPLIES)->mapOneToMany(
            $row,
            $object->replies,
            backreference: function (Reply $reply) use ($object) {
                $reply->rating = $object;
            }
        );
    }
}
