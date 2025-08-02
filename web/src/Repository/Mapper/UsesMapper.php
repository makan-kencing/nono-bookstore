<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

/**
 * @phpstan-require-extends RowMapper
 */
trait UsesMapper
{
    /**
     * The list of cached mappers.
     *
     * @var array<string, RowMapper>
     */
    public array $mappers = [];

    /**
     * Register and use a mapper for mapping nested entities.
     *
     * @template R of RowMapper
     * @param class-string<R> $mapperClass
     * @return R
     */
    public function useMapper(string $mapperClass, string $prefix = '')
    {
        $this->mappers[$prefix] ??= new $mapperClass($this->prefix . $prefix);
        return $this->mappers[$prefix];
    }
}
