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

readonly class UserCreateDTO extends RequestDTO
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
                UserRole::fromName($json['role'])
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

    public function toUser(): User
    {
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $user->role = $this->role;
        $user->isVerified = false;

        return $user;
    }
}
