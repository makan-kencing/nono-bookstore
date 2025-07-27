<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\UserProfile;
use DateTime;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

/**
 * @extends RowMapper<UserProfile>
 */
readonly class UserProfileRowMapper extends RowMapper
{
    public const string CONTACT_NO = 'contactNo';
    public const string DOB = 'dob';

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
    public function mapRow(array $row): UserProfile
    {
        $id = $this->getColumn($row, self::ID);
        assert(is_int($id));

        try {
            $userProfile = new UserProfile();
            $userProfile->id = $id;
            $this->bindProperties($userProfile, $row);
        } catch (OutOfBoundsException) {
            $userProfile = new UserProfile();
            $userProfile->id = $id;
            $userProfile->isLazy = true;
        }

        return $userProfile;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->contactNo = $this->getColumn($row, self::CONTACT_NO);
        $object->dob = ($v = $this->getColumn($row, self::DOB))
            ? DateTime::createFromFormat('Y-m-d', $v)
            : null;
    }
}
