<?php

declare(strict_types=1);

namespace App\Repository;

use App\Orm\Entity;
use App\Orm\QueryBuilder;
use App\Orm\ResultSetMapper;
use PDO;

/**
 * @template T of Entity
 */
abstract readonly class Repository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param QueryBuilder<T> $qb
     * @return T[]
     */
    public function get(QueryBuilder $qb): array
    {
        $stmt = $this->conn->prepare($qb->getQuery());
        foreach ($qb->getParameters() as $param => $value)
            $stmt->bindValue($param, $value);
        $stmt->execute();

        return $qb->getResultMapper()
            ->map($stmt);
    }

    /**
     * @param QueryBuilder<T> $qb
     * @return ?T
     */
    public function getOne(QueryBuilder $qb): ?Entity
    {
        $stmt = $this->conn->prepare($qb->getQuery());
        foreach ($qb->getParameters() as $param => $value)
            $stmt->bindValue($param, $value);
        $stmt->execute();

        return $qb->getResultMapper()
            ->mapOne($stmt);
    }

    /**
     * @param QueryBuilder<T> $qb
     * @return int
     */
    public function count(QueryBuilder $qb): int
    {
        $stmt = $this->conn->prepare($qb->getCount());
        foreach ($qb->getParameters() as $param => $value)
            $stmt->bindValue($param, $value);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }
}
