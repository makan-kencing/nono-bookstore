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
    /**
     * @inheritDoc
     */
    public function toSelectClauses(string $aliasPrefix = ''): array
    {
        $shortname = new ReflectionClass($this->class)->getShortName();
        return parent::toSelectClauses($shortname . '.');
    }
}
