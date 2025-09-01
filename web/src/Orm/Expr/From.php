<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Attribute\Transient;
use App\Orm\Entity;
use ReflectionClass;
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

    private function hasSelectClause(ReflectionProperty $reflectionProperty): bool
    {
        if ($reflectionProperty->getAttributes(Transient::class))
            return false;
        else if ($oneToAny = $reflectionProperty->getAttributes(OneToMany::class)
            ?: $reflectionProperty->getAttributes(OneToOne::class)) {
            assert(count($oneToAny) == 1);

            return $oneToAny[0]->newInstance()->mappedBy == null
                || array_key_exists($reflectionProperty->getName(), $this->joins);
        }
        return true;
    }

    /**
     * @return string[]
     */
    public function toSelectClauses(?string $columnPrefix = null): array
    {
        /** @var string[] $clauses */
        $clauses = [];
        $columnPrefix ??= self::toSnakeCase(new ReflectionClass($this->class)->getShortName()) . '.';

        $reflectionClass = new ReflectionClass($this->class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!$this->hasSelectClause($reflectionProperty))
                continue;
            $columnAlias = $columnPrefix;

            $join = $this->joins[$reflectionProperty->getName()] ?? null;

            if ($reflectionProperty->getAttributes(OneToMany::class)
                || $reflectionProperty->getAttributes(OneToOne::class)
                || $reflectionProperty->getAttributes(ManyToOne::class)) {
                $select = $this->alias . '.' . self::toSnakeCase($reflectionProperty->getName()) . '_id';

                if (!$join)
                    $columnAlias .= $reflectionProperty->getName() . '.id';
                else
                    $columnAlias .= $reflectionProperty->getName();
            } else {
                $select = $this->alias . '.' . self::toSnakeCase($reflectionProperty->getName());
                $columnAlias .= $reflectionProperty->getName();
            }

            if ($join)
                foreach ($join->toSelectClauses($columnAlias . '.') as $clause)
                    $clauses[] = $clause;
            else
                $clauses[] = $select . ' `' . $columnAlias . '`';
        }

        return $clauses;
    }

    public function toFromClause(): string
    {
        $clause = self::toSnakeCase(new ReflectionClass($this->class)->getShortName());
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

            $clause .= self::toSnakeCase(new ReflectionClass($join->class)->getShortName()) . ' ' . $join->alias;
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
