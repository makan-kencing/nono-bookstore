<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Rating\Reply;
use PDOStatement;

/**
 * @extends RowMapper<Reply>
 */
readonly class ReplyRowMapper extends RowMapper
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

        $reply = new Reply();

        $reply->id = $row[$prefix . 'id'];
        if ($reply->id == null) {
            return null;
        }

        $reply->user = $userRowMapper->mapRow($row, prefix: $prefix . 'user.');
        $reply->content = $row[$prefix . 'content'];

        return $reply;
    }
}
