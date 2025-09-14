<?php

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Response\ImageDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use UnexpectedValueException;

readonly class UserLoginContextDTO extends DTO
{
    public function __construct(
        public int $id,
        public string $sessionFlag,
        public string $username,
        public UserRole $role,
        public ?ImageDTO $image
    ) {
    }

    public static function fromUser(User $user): UserLoginContextDTO
    {
        return new self(
            $user->id ?? throw new UnexpectedValueException('Current logged in user does not have an id.'),
            $user->sessionFlag,
            $user->username,
            $user->role,
            $user->image !== null ? ImageDTO::fromFile($user->image) : null
        );
    }

    public function toUserReference(): User
    {
        $user = new User();
        $user->id = $this->id;
        return $user;
    }

    public function isStaff(): bool
    {
        return AuthRule::HIGHER_OR_EQUAL->check($this->role, UserRole::STAFF);
    }
}
