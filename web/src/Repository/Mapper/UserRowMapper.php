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
    private MembershipRowMapper $membershipRowMapper;

    public function __construct()
    {
        $this->userProfileRowMapper = new UserProfileRowMapper();
        $this->membershipRowMapper = new MembershipRowMapper();
    }

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = ''): array
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
    public function mapRow(array $row, string $prefix = ''): User
    {
        $id = $row[$prefix . 'id'];
        $user = new User();
        $user->id = $id;
        try {
            $user->username = $row[$prefix . 'username'];
            $user->email = $row[$prefix . 'email'];
            $user->hashedPassword = $row[$prefix . 'hashedPassword'];
            $user->role = UserRole::{$row[$prefix . 'role']};
            $user->isVerified = (bool)$row[$prefix . 'isVerified'];
            $user->profile = $this->userProfileRowMapper->mapRow($row, prefix: $prefix . 'profile.');
            $user->membership = $this->membershipRowMapper->mapRow($row, prefix: $prefix . 'membership.');
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $user = new User();
            $user->id = $id;
            $user->isLazy = true;
        }
        return $user;
    }
}
