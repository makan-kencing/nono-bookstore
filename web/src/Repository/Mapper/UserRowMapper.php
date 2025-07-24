<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use PDOStatement;
use Throwable;

/**
 * @extends RowMapper<User>
 */
readonly class UserRowMapper extends RowMapper
{
    private UserProfileRowMapper $userProfileRowMapper;

    public function __construct()
    {
        $this->userProfileRowMapper = new UserProfileRowMapper();
    }

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = '')
    {
        /** @var array<int, User> $userMap */
        $userMap = [];

        foreach ($stmt as $row) {
            $userId = $row[$prefix . 'id'];
            $user = $userMap[$userId] ?? null;

            if ($user == null) {
                $user = $this->mapRow($row, prefix: $prefix);

                $userMap[$userId] = $user;
            }
        }

        return array_values($userMap);
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

        $user = new User();
        $user->id = $id;
        try {
            $user->username = $row[$prefix . 'username'];
            $user->email = $row[$prefix . 'email'];
            $user->hashedPassword = $row[$prefix . 'hashedPassword'];
            $user->role = UserRole::{$row[$prefix . 'role']};
            $user->isVerified = (bool)$row[$prefix . 'isVerified'];
            $user->profile = $this->userProfileRowMapper->mapRow($row, prefix: $prefix . 'profile.');
        } catch (Throwable $e) {
            if (!str_contains($e->getMessage(), 'Undefined array key')) {
                throw $e;
            }

            $user = new User();
            $user->id = $id;
            $user->isLazy = true;
        }
        return $user;
    }
}
