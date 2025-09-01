<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User\UserRole;
use UnexpectedValueException;

readonly class UserLoginContextDTO extends DTO
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
        throw new UnexpectedValueException("Not implemented");
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        throw new UnexpectedValueException("Not implemented");
    }
}
