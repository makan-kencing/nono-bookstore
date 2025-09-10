<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Attribute\Transient;
use App\Orm\Entity;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * @template Z of Entity The source type
 * @template X of Entity The target type
 * @extends Expression<X>
 */
class From extends Expression
{
    /** @var array<string, Join<X, Entity>> */
    public array $joins = [];

    /**
     * @template Y of Entity
     * @param literal-string|ReflectionProperty $property
     * @param ?string $alias
     * @param JoinType $joinType
     * @return Join<X, Y>
     */
    public function join(
        string|ReflectionProperty $property,
        ?string                   $alias = null,
        JoinType                  $joinType = JoinType::INNER
    ): Join
    {
        if ($property instanceof ReflectionProperty) {
            $join = Join::fromProperty($property, $alias, $joinType);
            $property = $property->getName();
        } else
            $join = Join::fromClass($this->class, $property, $alias, $joinType);
        $this->joins[$property] = $join;
        return $join;
    }

    /**
     * @param string $aliasPrefix
     * @return string[]
     * @throws ReflectionException
     */
    public function toSelectClauses(string $aliasPrefix): array
    {
        /** @var string[] $clauses */
        $clauses = [];

        $reflectionClass = new ReflectionClass($this->class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $join = $this->joins[$reflectionProperty->getName()] ?? null;

            // check if have select
            if ($reflectionProperty->getAttributes(Transient::class))
                continue;

            $oneToOne = ($reflectionProperty->getAttributes(OneToOne::class)[0] ?? null)?->newInstance();
            $oneToMany = ($reflectionProperty->getAttributes(OneToMany::class)[0] ?? null)?->newInstance();
            $manyToOne = ($reflectionProperty->getAttributes(ManyToOne::class)[0] ?? null)?->newInstance();

            if ($oneToMany && $join == null)
                continue;

            if ($oneToOne && $oneToOne->mappedBy && $join == null)
                continue;

            // building select clause
            $alias = $aliasPrefix . $reflectionProperty->getName();
            $select = $this->alias . '.' . self::toSnakeCase($reflectionProperty->getName());

            if ($oneToOne || $oneToMany || $manyToOne) {
                $select .= '_id';

                if (!$join)
                    $alias .= '.id';
            }

            if ($join)
                foreach ($join->toSelectClauses($alias . '.') as $clause)
                    $clauses[] = $clause;
            else
                $clauses[] = $select . ' `' . $alias . '`';
        }

        return $clauses;
    }

    /**
     * @return string[]
     */
    public function toDistinctCountClauses(): array
    {

        /** @var string[] $clauses */
        $clauses = [];

        $reflectionClass = new ReflectionClass($this->class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!$reflectionProperty->getAttributes(Id::class))
                continue;

            $clause = $this->alias . '.' . self::toSnakeCase($reflectionProperty->getName());

            if ($reflectionProperty->getAttributes(ManyToOne::class)
                || $reflectionProperty->getAttributes(OneToOne::class))
                $clause .= '_id';

            $clauses[] = $clause;
        }

        return $clauses;
    }

    public function toFromClause(): string
    {
        $reflectionClass = new ReflectionClass($this->class);
        $clause = '`' . self::toSnakeCase($reflectionClass->getShortName()) . '`';
        if ($this->alias)
            $clause .= ' ' . $this->alias;
        return $clause;
    }

    /**
     * @return string[]
     */
    public function toJoinClauses(): array
    {
        /** @var string[] $clauses */
        $clauses = [];

        foreach ($this->joins as $property => $join) {
            $clause = match ($join->joinType) {
                JoinType::INNER => 'JOIN ',
                JoinType::LEFT => 'LEFT JOIN ',
                JoinType::RIGHT => 'RIGHT JOIN ',
            };

            $reflectionClass = new ReflectionClass($join->class);
            $clause .= '`' . self::toSnakeCase($reflectionClass->getShortName()) . '` ' . $join->alias;
            $clause .= ' ON ';

            if ($join->mappedBy)
                $clause .= $this->alias . '.id = ' . $join->alias . '.' . self::toSnakeCase($join->mappedBy) . '_id';
            else
                $clause .= $this->alias . '.' . self::toSnakeCase($property) . '_id = ' . $join->alias . '.id';

            $clauses[] = $clause;
            foreach ($join->toJoinClauses() as $clause)
                $clauses[] = $clause;
        }
        return $clauses;
    }
}
