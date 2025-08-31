<?php

declare(strict_types=1);

namespace App\Orm\Expr;

enum OrderDirection
{
    case ASCENDING;
    case DESCENDING;

    public function reversed(): OrderDirection
    {
        return $this == OrderDirection::ASCENDING ? OrderDirection::DESCENDING : OrderDirection::ASCENDING;
    }
}
