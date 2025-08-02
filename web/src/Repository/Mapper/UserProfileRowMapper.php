<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\UserProfile;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<UserProfile>
 */
class UserProfileRowMapper extends RowMapper
{
    public const string ID = self::USER . UserRowMapper::ID;
    public const string CONTACT_NO = 'contactNo';
    public const string DOB = 'dob';
    public const string USER = 'user.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): UserProfile
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $userProfile = new UserProfile();
            $this->bindProperties($userProfile, $row);
        } catch (OutOfBoundsException) {
            $userProfile = new UserProfile();
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
        $object->user = $this->useMapper(UserRowMapper::class, self::USER)->mapRow($row);
    }
}
