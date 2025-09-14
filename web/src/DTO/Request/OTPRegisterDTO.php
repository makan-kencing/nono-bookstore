<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Exception\BadRequestException;
use Throwable;

readonly class OTPRegisterDTO extends RequestDTO
{
    /**
     * @param non-empty-string $secret
     * @param non-empty-string $code
     */
    public function __construct(
        public string $secret,
        public string $code
    )
    {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): OTPRegisterDTO
    {
        try {
            return new self(
                $json['secret'],
                $json['code']
            );
        } catch (Throwable $e) {
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
