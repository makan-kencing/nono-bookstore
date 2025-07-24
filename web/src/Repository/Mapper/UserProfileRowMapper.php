<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\UserProfile;
use PDOStatement;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<UserProfile>
 */
readonly class UserProfileRowMapper extends RowMapper
{
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
    public function mapRow(array $row, string $prefix = ''): ?UserProfile
    {
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

        $userProfile = new UserProfile();
        $userProfile->id = $id;
        try {
            $userProfile->contactNo = $row[$prefix . 'contactNo'];
            $userProfile->dob = $row[$prefix . 'dob'];
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $userProfile = new UserProfile();
            $userProfile->id = $id;
            $userProfile->isLazy = $true;
        }

        return $userProfile;
    }
}
