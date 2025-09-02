<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use InvalidArgumentException;

readonly class PageRequest
{
    public function __construct(
        public int $page = 1,
        public int $pageSize = 1
    ) {
        if ($this->page < 1)
            throw new InvalidArgumentException('Page should be more than or equal to 1, not ' . $this->page);
        if ($this->pageSize < 1)
            throw new InvalidArgumentException('Page size should be more than or equal to 1, not ' . $this->pageSize);
    }
}
