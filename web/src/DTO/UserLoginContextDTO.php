<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User\User;
use App\Entity\User\UserRole;

readonly class UserLoginContextDTO extends DTO
{
    public function __construct(
        public int $id,
        public string $username,
        public UserRole $role
    ) {
    }

    public function toUserReference(): User
    {
        $user = new User();
        $user->id = $this->id;
        return $user;
    }
}
