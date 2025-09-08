<?php

declare(strict_types=1);

namespace App\DTO\Request;


use App\DTO\DTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;
use UnexpectedValueException;

readonly class UserUpdateDTO extends DTO
{
    public function __construct(
        public ?string $id = null,
        public ?string $username = null,
        public ?string $email = null,
        public ?UserRole $role = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): UserUpdateDTO
    {
        assert(is_array($json));

        try {
            return new self(
                $json['id'] ?? null,
                $json['username'] ?? null,
                $json['email'] ?? null,
                UserRole::{$json['role']} ?? null
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

        if (array_all(
            [$this->id, $this->username, $this->email],
            fn($val) => $val == null
        ))
            $rules[] = [
                "field" => "id",
                "type" => "NoIdentifiers",
                "reason" => "No identifiable information found"
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

    public function update(User $user): void
    {
        if ($this->email) {
            $user->email = $this->email;
            $user->isVerified = false;
        }

        if ($this->username)
            $user->username = $this->username;

        if ($this->role)
            $user->role = $this->role;
    }
}
