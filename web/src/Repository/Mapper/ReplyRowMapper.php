<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Reply;
use PDOStatement;
use DateTime;
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

        $reply = new Reply();
        $reply->id = $id;
        try {
            $reply->user = $this->userRowMapper->mapRow($row, prefix: $prefix . 'user.');
            $reply->content = $row[$prefix . 'content'];
            $reply->repliedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'repliedAt']);
        } catch (Throwable $e) {
            if (!str_contains($e->getMessage(), 'Undefined array key')) {
                throw $e;
            }

            $reply = new Reply();
            $reply->id = $id;
            $reply->isLazy = true;
        }

        return $reply;
    }
}
