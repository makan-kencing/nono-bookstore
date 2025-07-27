<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Rating;
use OutOfBoundsException;
use PDOStatement;
use DateTime;
use RuntimeException;

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

    private ReplyRowMapper $replyRowMapper;
    private UserRowMapper $userRowMapper;
    private BookRowMapper $bookRowMapper;

    public function getReplyRowMapper(): ReplyRowMapper
    {
        $this->replyRowMapper ??= new ReplyRowMapper($this->prefix . self::REPLIES);
        return $this->replyRowMapper;
    }

    public function getUserRowMapper(): UserRowMapper
    {
        $this->userRowMapper ??= new UserRowMapper($this->prefix . self::USER);
        return $this->userRowMapper;
    }

    public function getBookRowMapper(): BookRowMapper
    {
        $this->bookRowMapper ??= new BookRowMapper($this->prefix . self::BOOK);
        return $this->bookRowMapper;
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
        if ($v = $this->getUserRowMapper()->mapRowOrNull($row)) {
            $object->user = $v;
        }
        if ($v = $this->getBookRowMapper()->mapRowOrNull($row)) {
            $object->book = $v;
        }
    }
}
