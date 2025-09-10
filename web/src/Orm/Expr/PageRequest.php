<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use InvalidArgumentException;

readonly class PageRequest
{
    public function __construct(
        public int $page = 1,
        public int $pageSize = 10
    ) {
        if ($this->page < 1)
            throw new InvalidArgumentException('Page should be more than or equal to 1, not ' . $this->page);
        if ($this->pageSize < 1)
            throw new InvalidArgumentException('Page size should be more than or equal to 1, not ' . $this->pageSize);
    }

    public function getStartIndex(): int
    {
        return ($this->page - 1) * $this->pageSize;
    }

    public function getEndIndex(): int
    {
        return $this->page * $this->pageSize - 1;
    }

    public function indexIn(int $index): bool
    {
        return $this->getStartIndex() <= $index
            && $index <= $this->getEndIndex();
    }

    public function getPrevious(): PageRequest
    {
        return new PageRequest(max($this->page - 1, 1), $this->pageSize);
    }

    public function getNext(): PageRequest
    {
        return new PageRequest($this->page + 1, $this->pageSize);
    }
}
