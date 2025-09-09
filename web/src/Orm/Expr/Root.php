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
    public function getRootPrefix(): string
    {
        $reflectionClass = new ReflectionClass($this->class);
        $shortname = $reflectionClass->getShortName();
        return $shortname . '.';
    }

    /**
     * @inheritDoc
     */
    public function toSelectClauses(string $aliasPrefix = ''): array
    {
        return parent::toSelectClauses($this->getRootPrefix());
    }
}
