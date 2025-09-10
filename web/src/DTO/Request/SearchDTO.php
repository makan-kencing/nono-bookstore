<?php

declare(strict_types=1);

namespace App\DTO\Request;

readonly class SearchDTO extends PaginationDTO
{
    public function __construct(
        public ?string $query = null,
        int $page = 1,
        int $pageSize = 50
    ) {
        parent::__construct($page, $pageSize);
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): SearchDTO
    {
        return new self(
            $json['query'] ?? null,
            (int) ($json['page'] ?? 1),
            (int) ($json['page_size'] ?? 50),
        );
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        // TODO: Implement validate() method.
    }
}
