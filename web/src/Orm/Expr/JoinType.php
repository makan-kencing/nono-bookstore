<?php

declare(strict_types=1);

namespace App\Orm\Expr;

enum JoinType
{
    case INNER;
    case LEFT;
    case RIGHT;
}
