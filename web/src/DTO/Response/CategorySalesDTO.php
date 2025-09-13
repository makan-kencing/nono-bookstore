<?php

namespace App\DTO\Response;

use UnexpectedValueException;

readonly class CategorySalesDTO extends ResponseDTO
{
    public function __construct(
        public string $categoryName,
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
