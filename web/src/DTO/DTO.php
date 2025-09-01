<?php

declare(strict_types=1);

namespace App\DTO;

use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use JsonSerializable;

abstract readonly class DTO implements JsonSerializable
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
}
