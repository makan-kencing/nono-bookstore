<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use PDOStatement;
use Throwable;

/**
 * @template T
 */
abstract readonly class RowMapper
{
    /**
     * Consume a result set to return a list of T
     *
     * @param PDOStatement $stmt
     * @param string $prefix
     * @return T[]
     */
    abstract public function map(PDOStatement $stmt, string $prefix = ''): array;

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param array<int|string,mixed> $row
     * @param string $prefix
     * @return ?T
     */
    abstract public function mapRow(array $row, string $prefix = ''): mixed;

    /**
     * Returns true if the exception is related to an invalid array key access.
     * @param Throwable $e
     * @return bool
     */
    protected function isInvalidArrayAccess(Throwable $e): bool
    {
        return str_contains($e->getMessage(), 'Undefined array key');
    }
}
