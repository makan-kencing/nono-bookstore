<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\ABC\Entity;
use OutOfBoundsException;
use PDOStatement;

/**
 * @template T of Entity
 */
abstract class RowMapper
{
    public const string ID = 'id';

    public readonly string $prefix;

    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * Consume a result set to return a list of T
     *
     * @param PDOStatement $stmt
     * @return T[]
     */
    abstract public function map(PDOStatement $stmt): array;

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param array<int|string, mixed> $row
     * @return T
     * @throws OutOfBoundsException
     */
    abstract public function mapRow(array $row): mixed;

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param array<int|string, mixed> $row
     * @return ?T
     */
    public function mapRowOrNull(array $row): mixed
    {
        try {
            return $this->mapRow($row);
        } catch (OutOfBoundsException) {
            return null;
        }
    }

    /**
     * Utility function for easily mapping one-to-many entities
     *
     * @param array<int|string, mixed> $row
     * @param ?array<T> &$attribute
     * @param ?callable(T): void $backreference The function to be called when mapped the first time.
     * @param (callable(T): void)[] $nested The functions to be called regardless of first-time mapping.
     * @param-out array<T> $attribute
     * @return void
     */
    public function mapOneToMany(
        array $row,
        ?array &$attribute,
        ?callable $backreference = null,
        array $nested = []
    ): void {
        // initialize array if not already
        $attribute ??= [];

        $id = $row[$this->prefix . static::ID] ?? null;
        if ($id) {
            // check if already defined
            $child = $attribute[$id] ?? null;

            // if not, map it
            if ($child == null) {
                $child = $this->mapRow($row);
                if ($backreference) {
                    $backreference($child);
                }

                $attribute[$id] = $child;
            }

            foreach ($nested as $f) {
                $f($child);
            }
        }
    }

    /**
     * @param T $object
     * @param array<int|string, mixed> $row
     * @return void
     * @throws OutOfBoundsException
     */
    abstract public function bindProperties(mixed $object, array $row): void;

    /**
     * A utility function to get the column by name prefixed by prefix.
     *
     * @template TKey
     * @template TValue
     * @param array<TKey, TValue> $row
     * @param TValue $key
     * @return ?TValue
     * @throws OutOfBoundsException
     */
    public function getColumn(array $row, mixed $key): mixed
    {
        if (!array_key_exists($this->prefix . $key, $row)) {
            throw new OutOfBoundsException("`$this->prefix$key` is not found.");
        }
        return $row[$this->prefix . $key];
    }
}
