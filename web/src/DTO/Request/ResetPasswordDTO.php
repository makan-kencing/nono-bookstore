<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Entity\User\User;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class ResetPasswordDTO extends RequestDTO
{
    public function __construct(
        public ?string $token = null,
        public ?string $newPassword = null,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        assert(is_array($json));

        try {
            return new self(
                $json['token'] ?? null,
                $json['new_password'] ?? $json['newPassword'] ?? null,
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
        $errors = [];

        if (!$this->token) {
            $errors[] = [
                "field" => "token",
                "type" => "required",
                "reason" => "Token is required"
            ];
        }

        if (!$this->newPassword || strlen($this->newPassword) < 8) {
            $errors[] = [
                "field" => "new_password",
                "type" => "TooShort",
                "reason" => "Password must be at least 8 characters"
            ];
        }

        if ($errors) {
            throw new UnprocessableEntityException($errors);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function apply(User $user): void
    {
        $user->password = password_hash($this->newPassword, PASSWORD_DEFAULT);
    }
}
