<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Reply;
use PDOStatement;
use DateTime;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<Reply>
 */
readonly class ReplyRowMapper extends RowMapper
{
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
    public function mapRow(array $row, string $prefix = ''): Reply
    {
        $id = $row[$prefix . 'id'];
        $reply = new Reply();
        $reply->id = $id;
        try {
            $reply->user = $this->userRowMapper->mapRow($row, prefix: $prefix . 'user.');
            $reply->content = $row[$prefix . 'content'];
            $reply->repliedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'repliedAt']);
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $reply = new Reply();
            $reply->id = $id;
            $reply->isLazy = true;
        }

        return $reply;
    }
}
