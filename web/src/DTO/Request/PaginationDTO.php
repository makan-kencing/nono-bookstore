<?php

namespace App\DTO\Request;

use App\Exception\UnprocessableEntityException;
use App\Orm\Expr\PageRequest;

readonly class PaginationDTO extends RequestDTO
{
    public function __construct(
        public int $page = 1,
        public int $pageSize = 50
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        return new self(
            (int) ($json['page'] ?? 1),
            (int) ($json['page_size'] ?? 50),
        );
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {

        $rules = [];
        if ($this->page < 1)
            $rules[] = [
                "field" => "page",
                "type" => "page",
                "reason" => "Page must be more than or equal to 1"
            ];

        if ($this->pageSize < 1)
            $rules[] = [
                "field" => "page_size",
                "type" => "page_size",
                "reason" => "Page size must be more than or equal to 1"
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    public function toPageRequest(): PageRequest
    {
        return new PageRequest($this->page, $this->pageSize);
    }
}
