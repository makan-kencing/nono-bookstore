<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use App\Orm\Attribute\Transient;
use App\Orm\Entity;
use ReflectionClass;

/**
 * @template X of Entity The target type
 * @extends From<X, X>
 */
class Root extends From
{
}
