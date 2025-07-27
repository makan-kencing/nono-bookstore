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

        $id = $row[$this->prefix . self::ID] ?? null;
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
     * Map one-to-one relationship if exists, else is unassigned.
     *
     * @param array<int|string, mixed> $row
     * @param T $attribute
     * @param ?callable(T): void $backreference
     * @param (callable(T): void)[] $nested
     * @return void
     */
    public function mapOneToOne(
        array $row,
        mixed &$attribute,
        ?callable $backreference = null,
        array $nested = []
    ): void {
        $id = $row[$this->prefix . self::ID] ?? null;
        if ($id) {
            $child = $this->mapRow($row);
            if ($backreference) {
                $backreference($child);
            }

            $attribute = $child;

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
     * @template TValue
     * @param array<int|string, TValue> $row
     * @param string $key
     * @return ?TValue
     * @throws OutOfBoundsException
     */
    public function getColumn(array $row, string $key): mixed
    {
        return $row[$this->prefix . $key] ?? throw new OutOfBoundsException();
    }
}
