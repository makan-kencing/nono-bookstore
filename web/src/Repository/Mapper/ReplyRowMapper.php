<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Reply;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<Reply>
 */
class ReplyRowMapper extends RowMapper
{
    public const string CONTENT = 'content';
    public const string REPLIED_AT = 'repliedAt';
    public const string USER = 'user.';
    public const string RATING = 'rating.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Reply
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $reply = new Reply();
            $reply->id = $id;
            $this->bindProperties($reply, $row);
        } catch (OutOfBoundsException) {
            $reply = new Reply();
            $reply->id = $id;
            $reply->isLazy = true;
        }

        return $reply;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->content = $this->getColumn($row, self::CONTENT);
        $object->repliedAt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::REPLIED_AT)
        );
        if ($v = $this->useMapper(UserRowMapper::class, self::USER)->mapRowOrNull($row)) {
            $object->user = $v;
        }
        if ($v = $this->useMapper(RatingRowMapper::class, self::RATING)->mapRowOrNull($row)) {
            $object->rating = $v;
        }
    }
}
