<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Exception\BadRequestException;
use Throwable;
use UnexpectedValueException;

readonly class UserLoginDTO extends RequestDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $rememberMe = false,
        public ?string $otp = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): UserLoginDTO
    {
        assert(is_array($json));

        try {
            return new self(
                $json['email'],
                $json['password'],
                array_key_exists('remember_me', $json),
                $json['otp'] ?? null
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
    }
}
