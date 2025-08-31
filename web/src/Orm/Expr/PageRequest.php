<?php

declare(strict_types=1);

namespace App\Orm\Expr;

readonly class PageRequest
{
    public function __construct(
        public int $size,
        public int $page = 1
    ) {
    }
}
