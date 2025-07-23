<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

/**
 * @template T
 */
abstract readonly class Repository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param array<int|string,mixed> $row
     * @param string $prefix
     * @return T
     */
    abstract public function mapRow(mixed $row, string $prefix = '');
}
