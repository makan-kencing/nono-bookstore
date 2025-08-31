<?php

declare(strict_types=1);

namespace App\Orm\Expr;

enum PredicateOperator
{
    case AND;
    case OR;
}
