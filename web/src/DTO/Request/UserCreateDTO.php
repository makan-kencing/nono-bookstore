<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;
use UnexpectedValueException;

readonly class UserCreateDTO extends DTO
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public UserRole $role,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): UserCreateDTO
    {
        assert(is_array($json));

        try {
            return new self(
                $json['username'],
                $json['email'],
                $json['password'],
                UserRole::{$json['role']}
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $rules = [];
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            $rules[] = [
                "field" => "email",
                "type" => "email",
                "reason" => "Invalid email format"
            ];

        if (strlen($this->password) < 12)
            $rules[] = [
                "field" => "password",
                "type" => "length",
                "reason" => "Less than 12 characters long"
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        throw new UnexpectedValueException("Not implemented");
    }
}
