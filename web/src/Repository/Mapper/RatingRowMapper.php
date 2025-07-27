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
readonly class RatingRowMapper extends RowMapper
{
    public const string RATING = 'rating';
    public const string TITLE = 'title';
    public const string CONTENT = 'content';
    public const string RATED_AT = 'ratedAt';
    public const string REPLIES = 'replies.';
    public const string USER = 'user.';
    public const string BOOK = 'book.';

    public ReplyRowMapper $replyRowMapper;
    public UserRowMapper $userRowMapper;
    public BookRowMapper $bookRowMapper;

    public function __construct(string $prefix)
    {
        parent::__construct($prefix);
        $this->replyRowMapper = new ReplyRowMapper($prefix . self::REPLIES);
        $this->userRowMapper = new UserRowMapper($prefix . self::USER);
        $this->bookRowMapper = new BookRowMapper($prefix . self::BOOK);
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
        $this->userRowMapper->mapOneToOne($row, $object->user);
        $this->bookRowMapper->mapOneToOne($row, $object->book);
        $object->rating = $this->getColumn($row, self::RATING);
        $object->title = $this->getColumn($row, self::TITLE);
        $object->content = $this->getColumn($row, self::CONTENT);
        $object->ratedAt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::RATED_AT)
        );
    }
}
