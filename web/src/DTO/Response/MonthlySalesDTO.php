<?php

namespace App\DTO\Response;

use UnexpectedValueException;

readonly class MonthlySalesDTO extends ResponseDTO
{
    public function __construct(
        public string $yearMonth,
        public int $quantity,
        public int $revenue,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        throw new UnexpectedValueException('Not implemented');
    }
}
