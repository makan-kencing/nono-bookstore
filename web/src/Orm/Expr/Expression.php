<?php

declare(strict_types=1);

namespace App\Orm\Expr;

/**
 * @template T
 */
class Expression
{
    /** @var class-string<T> */
    public readonly string $class;
    public readonly string $alias;

    /**
     * @param class-string<T> $class
     * @param ?string $alias
     */
    public function __construct(string $class, ?string $alias = null)
    {
        $this->class = $class;
        $this->alias = $alias ?? strtolower($class);
    }

    public static function toSnakeCase(string $camelCase): string
    {
        $camelCase = preg_replace('/(?<!^)[A-Z]/', '_$0', $camelCase);
        assert(is_string($camelCase));

        return strtolower($camelCase);
    }
}
