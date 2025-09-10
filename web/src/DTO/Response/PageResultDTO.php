<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\DTO\Response\ResponseDTO;
use App\Orm\Expr\PageRequest;
use UnexpectedValueException;

/**
 * @template T
 */
readonly class PageResultDTO extends ResponseDTO
{
    /**
     * @param T[] $items
     * @param int $total
     * @param PageRequest $pageRequest
     */
    public function __construct(
        public array $items,
        public int $total,
        public PageRequest $pageRequest
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        throw new UnexpectedValueException('Not implemented');
    }

    public function hasPreviousPage(): bool
    {
        return $this->pageRequest->page > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->pageRequest->page < $this->getTotalPage();
    }

    public function getStartIndex(): int
    {
        return $this->pageRequest->getStartIndex();
    }

    public function getEndIndex(): int
    {
        return min($this->pageRequest->getEndIndex(), $this->total - 1);
    }

    public function getTotalPage(): int
    {
        return (int)(($this->total - 1) / $this->pageRequest->pageSize) + 1;
    }

    /**
     * @param int $n
     * @return PageRequest[]
     */
    public function getSlidingPageWindow(int $n = 5): array
    {
        $d = $n / 2;

        $min = $this->pageRequest->page - (int) $d;
        $max = $this->pageRequest->page + (int) $d;

        $offset = min($min, 1) - 1;
        if ($offset == 0)
            $offset = max($max - $this->getTotalPage(), 0);

        $min -= $offset;
        $max -= $offset;

        $min = max($min, 1);
        $max = min($max, $this->getTotalPage());

        $pages = [];
        for ($page = $min; $page <= $max; $page++)
            $pages[] = new PageRequest($page, $this->pageRequest->pageSize);
        return $pages;
    }
}
