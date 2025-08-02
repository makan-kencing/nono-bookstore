<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\ABC\Entity;
use OutOfBoundsException;
use PDOStatement;

/**
 * @template T of Entity
 * @phpstan-type Row array<string, mixed>
 */
abstract class RowMapper
{
    use UsesMapper;

    public const string ID = 'id';

    public function __construct(
        public readonly string $prefix = ''
    ) {
    }

    /**
     * A utility function to get the column by name prefixed by prefix.
     *
     * @template R of Row
     * @param R $row
     * @param key-of<R> $key
     * @return ?value-of<R>
     * @throws OutOfBoundsException
     */
    public function getColumn(array $row, mixed $key): mixed
    {
        if (!array_key_exists($this->prefix . $key, $row)) {
            throw new OutOfBoundsException("`$this->prefix$key` is not found.");
        }
        return $row[$this->prefix . $key];
    }

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param Row $row
     * @return T
     * @throws OutOfBoundsException
     */
    abstract public function mapRow(array $row): mixed;

    /**
     * Map a row from PDOStatement into an instance of T.
     *
     * @param Row $row
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
     * @param Row $row
     * @param array<T> &$attribute
     * @param ?callable(T): void $backreference The function to be called when mapped the first time.
     * @param-immediately-invoked-callable $backreference
     * @param-out array<T> $attribute
     * @return void
     */
    public function mapOneToMany(
        array $row,
        array &$attribute,
        ?callable $backreference = null
    ): void {
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

            $this->bindOneToManyProperties($child, $row);
        }
    }

    /**
     * @param T $object
     * @param Row $row
     * @return void
     * @throws OutOfBoundsException
     */
    abstract public function bindProperties(mixed $object, array $row): void;

    /**
     * @param T $object
     * @param Row $row
     * @return void
     */
    public function bindOneToManyProperties(mixed $object, array $row): void
    {
    }

    /**
     * Consume a result set to return a list of T
     *
     * @param PDOStatement $stmt
     * @return T[]
     */
    public function extract(PDOStatement $stmt): array
    {
        /** @var array<int, T> $map */
        $map = [];
        foreach ($stmt as $row) {
            $this->mapOneToMany(
                $row,
                $map
            );
        }

        return array_values($map);
    }
}
