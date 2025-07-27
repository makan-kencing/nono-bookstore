<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Reply;
use DateTime;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

/**
 * @extends RowMapper<Reply>
 */
class ReplyRowMapper extends RowMapper
{
    public const string CONTENT = 'content';
    public const string REPLIED_AT = 'repliedAt';
    public const string USER = 'user.';
    public const string RATING = 'rating.';

    private UserRowMapper $userRowMapper;
    private RatingRowMapper $ratingRowMapper;

    public function getUserRowMapper(): UserRowMapper
    {
        $this->userRowMapper ??= new UserRowMapper($this->prefix . self::USER);
        return $this->userRowMapper;
    }

    public function getRatingRowMapper(): RatingRowMapper
    {
        $this->ratingRowMapper ??= new RatingRowMapper($this->prefix . self::RATING);
        return $this->ratingRowMapper;
    }

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
    public function mapRow(array $row): Reply
    {
        $id = $this->getColumn($row, self::ID);
        assert(is_int($id));

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
        $this->getUserRowMapper()->mapOneToOne($row, $object->user);
        $this->getRatingRowMapper()->mapOneToOne($row, $object->rating);
        $object->content = $this->getColumn($row, self::CONTENT);
        $object->repliedAt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::REPLIED_AT)
        );
    }
}
