<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;

abstract readonly class RequestDTO extends DTO
{
    /**
     * @param mixed $json
     * @return self
     * @throws BadRequestException
     */
    abstract public static function jsonDeserialize(mixed $json): self;

    /**
     * @return void
     * @throws UnprocessableEntityException
     */
    abstract public function validate(): void;

    public static function toInt(string|int|null $val): ?int
    {
        if ($val === null) return null;
        return intval($val);
    }
}
