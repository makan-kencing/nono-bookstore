<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use PDOStatement;

/**
 * @extends RowMapper<User>
 */
readonly class UserRowMapper extends RowMapper
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
        $user = new User();

        $user->id = $row[$prefix . 'id'];
        $user->username = $row[$prefix . 'username'];
        $user->email = $row[$prefix . 'email'];
        $user->hashedPassword = $row[$prefix . 'hashedPassword'];
        $user->role = UserRole::{$row[$prefix . 'role']};
        $user->isVerified = (bool)$row[$prefix . 'isVerified'];

        return $user;
    }
}
