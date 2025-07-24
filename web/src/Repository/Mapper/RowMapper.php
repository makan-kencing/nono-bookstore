<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use PDOStatement;

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
    abstract public function map(PDOStatement $stmt, string $prefix = '');

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param array<int|string,mixed> $row
     * @param string $prefix
     * @return T
     */
    abstract public function mapRow(mixed $row, string $prefix = '');
}
