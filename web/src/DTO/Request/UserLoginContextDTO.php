<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Entity\User\UserRole;

readonly class UserLoginContextDTO extends RequestDTO
{
    public function __construct(
        public string $username,
        public UserRole $role
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): UserLoginContextDTO
    {
        return new self(
            $json['username'],
            UserRole::{$json['role']}
        );
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'username' => $this->username,
            'role' => $this->role->name
        ];
    }
}
