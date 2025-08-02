<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use OutOfBoundsException;

/**
 * @extends RowMapper<User>
 */
class UserRowMapper extends RowMapper
{
    public const string USERNAME = 'username';
    public const string EMAIL = 'email';
    public const string HASHED_PASSWORD = 'hashedPassword';
    public const string ROLE = 'role';
    public const string IS_VERIFIED = 'isVerified';
    public const string PROFILE = 'profile.';
    public const string MEMBERSHIP = 'membership.';
    public const string DEFAULT_ADDRESS = 'defaultAddress.';
    public const string ADDRESSES = 'addresses.';
    public const string WISHLIST = 'wishlist.';
    public const string CART = 'cart.';
    public const string ORDERS = 'orders.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): User
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $user = new User();
            $user->id = $id;
            $this->bindProperties($user, $row);
        } catch (OutOfBoundsException) {
            $user = new User();
            $user->id = $id;
            $user->isLazy = true;
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->username = $this->getColumn($row, self::USERNAME);
        $object->email = $this->getColumn($row, self::EMAIL);
        $object->hashedPassword = $this->getColumn($row, self::HASHED_PASSWORD);
        $object->role = UserRole::{$this->getColumn($row, self::ROLE)};
        $object->isVerified = (bool)$this->getColumn($row, self::IS_VERIFIED);
        $object->profile = $this->useMapper(UserProfileRowMapper::class, self::PROFILE)->mapRowOrNull($row);
        $object->membership = $this->useMapper(MembershipRowMapper::class, self::MEMBERSHIP)->mapRowOrNull($row);
    }
}
