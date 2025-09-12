<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class UserPasswordUpdateDTO extends RequestDTO
{
    public function __construct(
        public string $oldPassword,
        public string $newPassword,
        public string $confirmPassword,
    ) {
    }

    public static function jsonDeserialize(mixed $json): self
    {
        assert(is_array($json));
        try {
            return new self(
                $json['old_password'] ?? '',
                $json['new_password'] ?? '',
                $json['confirm_password'] ?? ''
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }

    public function validate(): void
    {
        $rules = [];

        if (strlen($this->newPassword) < 8) {
            $rules[] = [
                "field" => "new_password",
                "type" => "length",
                "reason" => "Password must be at least 8 characters long"
            ];
        }

        if ($this->newPassword !== $this->confirmPassword) {
            $rules[] = [
                "field" => "confirm_password",
                "type" => "mismatch",
                "reason" => "Passwords do not match"
            ];
        }

        if ($rules) {
            throw new UnprocessableEntityException($rules);
        }
    }
}
