<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\UserProfile;
use PDOStatement;

/**
 * @extends RowMapper<UserProfile>
 */
readonly class UserProfileRowMapper extends RowMapper
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
        // TODO: Implement mapRow() method.

        $userProfile = new UserProfile();

        $userProfile->contactNo = $row[$prefix.'contactNo'];
        $userProfile->dob= $row[$prefix.'dob'];

        return $userProfile;

    }
}
