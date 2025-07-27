<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\ABC\Entity;
use PDOStatement;
use Throwable;
use OutOfRangeException;

/**
 * @template T of Entity
 */
abstract readonly class RowMapper
{
    public const string ID = 'id';

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
     * @return T
     * @throws OutOfRangeException
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

    /**
     * Utility function for easily mapping one-to-many entities
     *
     * @template K of Entity
     * @param array<int|string, mixed> $row
     * @param ?array<K> &$attribute
     * @param RowMapper<K> $mapper
     * @param string $prefix
     * @param ?callable(K): void $backreference
     * @param-out array<K> $attribute
     * @return void
     */
    public static function mapOneToMany(
        array $row,
        ?array &$attribute,
        RowMapper $mapper,
        string $prefix = '',
        ?callable $backreference = null
    ): void {
        // initialize array if not already
        $attribute ??= [];

        $id = $row[$prefix . $mapper::ID] ?? null;
        if ($id) {
            // check if already defined
            $child = $attribute[$id] ?? null;

            // if not, map it
            if ($child == null) {
                $child = $mapper->mapRow($row, prefix: $prefix);
                if ($backreference) {
                    $backreference($child);
                }

                $attribute[$id] = $child;
            }
        }
    }
}
