<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Exception\BadRequestException;
use Throwable;
use UnexpectedValueException;

readonly class UserLoginDTO extends DTO
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
                $json['remember_me'] ?? false,
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

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        throw new UnexpectedValueException("Not implemented");
    }
}
