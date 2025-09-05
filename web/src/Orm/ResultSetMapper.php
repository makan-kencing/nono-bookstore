<?php

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Attribute\Transient;
use App\Orm\Expr\From;
use App\Orm\Expr\PageRequest;
use App\Orm\Expr\Root;
use Closure;
use DateTime;
use InvalidArgumentException;
use PDOStatement;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use UnexpectedValueException;

/**
 * @template T of Entity
 * @phpstan-type Row array<string, mixed>
 */
class ResultSetMapper
{
    /**
     * @var Root<T>
     */
    public readonly Root $root;
    public readonly ?PageRequest $pageRequest;

    /** @var Row */
    private ?array $processingRow = null;
    private int $index = 0;

    /**
     * @template V
     * @var array<class-string<V>, Closure(mixed): ?V >
     */
    public static array $converters = [];

    /**
     * @param Root<T> $root
     */
    public function __construct(Root $root, ?PageRequest $pageRequest)
    {
        $this->root = $root;
        $this->pageRequest = $pageRequest;

        if (!self::$converters)  // defaults converters
            self::$converters = [
                'string' => fn($val) => strval($val),
                'int' => fn($val) => intval($val),
                'bool' => fn($val) => boolval($val),
                DateTime::class => fn($val) => DateTime::createFromFormat('Y-m-d H:i:s', strval($val)) ?: null
            ];
    }

    /**
     * @param PDOStatement $stmt
     * @return T[]
     */
    public function map(PDOStatement $stmt): array
    {
        /** @vat T[] $entities */
        $entities = [];
        while (true) {
            if ($this->processingRow == null) {
                $row = $stmt->fetch();
                if (!$row)
                    break;
                $this->processingRow = $row;
            }

            if ($this->pageRequest != null && !$this->pageRequest->indexIn($this->index++)) {
                $this->processingRow = null;
                continue;
            }

            $this->mapToMany($this->root, $entities, $this->root->getRootPrefix());
            $this->processingRow = null;
        }

        return $entities;
    }

    /**
     * @param PDOStatement $stmt
     * @return ?T
     */
    public function mapOne(PDOStatement $stmt): ?Entity
    {
        // TODO: rewrite this shit to be readable
        /** @var T[] $entities */
        $entity = null;
        $entities = [];
        while (true) {
            if ($this->processingRow == null) {
                $row = $stmt->fetch();
                if (!$row)
                    break;
                $this->processingRow = $row;
            }

            if ($this->pageRequest != null && !$this->pageRequest->indexIn($this->index++)) {
                $this->processingRow = null;
                continue;
            }

            $e = $this->mapToMany($this->root, $entities, $this->root->getRootPrefix());

            $entity ??= $e;
            if ($entity !== $e)
                return $entity;

            $this->processingRow = null;
        }
        return $entity;
    }

    /**
     * @template E of Entity
     * @param From<Entity, E> $from
     * @param array<int, E> &$mapping
     * @param string $aliasPrefix
     * @return ?E
     * @throws ReflectionException
     */
    private function mapToMany(From $from, array &$mapping, string $aliasPrefix): ?Entity
    {
        $hash = $this->getHash($from->class, $aliasPrefix);
        if ($hash === null)
            return null;

        $entity = $mapping[$hash] ?? null;

        if ($entity === null) {
            $entity = new $from->class();
            $mapping[$hash] = $entity;
        }

        $this->mapToOne($from, $entity, $aliasPrefix);
        return $entity;
    }

    /**
     * @template E of Entity
     * @param From<Entity, E> $from
     * @param ?E &$entity
     * @param string $aliasPrefix
     * @return ?E
     * @throws ReflectionException
     */
    private function mapToOne(From $from, ?Entity &$entity, string $aliasPrefix): ?Entity
    {
        assert($this->processingRow !== null);

        if ($this->getHash($from->class, $aliasPrefix) === null)
            return null;

        $reflectionClass = new ReflectionClass($from->class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            assert($reflectionProperty->getType() !== null);

            $entity ??= new $from->class();

            $type = $reflectionProperty->getType()->getName();
            $property = $reflectionProperty->getName();
            $alias = $aliasPrefix . $property;

            $join = $from->joins[$property] ?? null;

            // check if have select
            if ($reflectionProperty->getAttributes(Transient::class))
                continue;

            $oneToOne = ($reflectionProperty->getAttributes(OneToOne::class)[0] ?? null)?->newInstance();
            $oneToMany = ($reflectionProperty->getAttributes(OneToMany::class)[0] ?? null)?->newInstance();
            $manyToOne = ($reflectionProperty->getAttributes(ManyToOne::class)[0] ?? null)?->newInstance();

            if ($oneToMany && $join === null)
                continue;

            if ($oneToOne && $oneToOne->mappedBy && $join == null)
                continue;

            // do mapping
            if ($oneToMany) {
                $entity->$property ??= [];

                assert(is_array($entity->$property));
                $related = $this->mapToMany($join, $entity->$property, $alias . '.');
                if ($related !== null)
                    $related->{$oneToMany->mappedBy} = $entity;
            } else if ($oneToOne || $manyToOne)
                if (!$join) {
                    if (isset($entity->$property))
                        continue;

                    $alias .= '.id';

                    $rawValue = $this->processingRow[$alias];
                    if ($rawValue === null)
                        $entity->$property = null;
                    else {
                        $entity->$property = new $type();
                        $entity->$property->id = $this->convertValue('int', $rawValue);
                    }
                } else {
                    $related = $entity->$property ?? null;
                    $related = $entity->$property = $this->mapToOne($join, $related, $alias . '.');
                    if ($related !== null)
                        if ($oneToOne && $oneToOne->mappedBy)
                            $related->{$oneToOne->mappedBy} = $entity;
                }
            else {
                // normal column values
                if (isset($entity->$property))
                    continue;

                $rawValue = $this->processingRow[$alias];
                if ($rawValue === null)
                    $entity->$property = null;
                else {
                    $value = $this->convertValue($type, $rawValue);
                    $entity->$property = $value;
                }
            }
        }

        return $entity;
    }

    /**
     * @template E of Entity
     * @var class-string<E> $class
     * @var string $aliasPrefix
     * @return ?int
     * @throws ReflectionException
     */
    public function getHash(string $class, string $aliasPrefix): ?int
    {
        assert($this->processingRow != null);

        $hash = null;
        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!$reflectionProperty->getAttributes(Id::class))
                continue;

            $alias = $aliasPrefix . $reflectionProperty->getName();
            if ($reflectionProperty->getAttributes(OneToOne::class)
                || $reflectionProperty->getAttributes(ManyToOne::class))
                $alias .= '.id';

            $rawValue = $this->processingRow[$alias];
            if ($rawValue == null)
                continue;

            $value = $this->convertValue('int', $rawValue);

            $hash ??= 0;
            $hash += $value;
        }

        return $hash;
    }

    /**
     * @template V
     * @param class-string<V> $type
     * @param mixed $val
     * @return V
     */
    public function convertValue(string $type, mixed $val)
    {
        $converter = self::$converters[$type] ?? null;
        if ($converter)
            return $converter($val);

        if (enum_exists($type) && is_string($val))
            try {
                $enum = new ReflectionEnum($type);
                return $enum->getCase($val)->getValue();
            } catch (ReflectionException) {
                throw new UnexpectedValueException('The case `' . $val . '` is not found in ' . $type . '.');
            }

        throw new UnexpectedValueException('The type of `' . $type . '` is not supported.');
    }
}
