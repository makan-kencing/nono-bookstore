<?php

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Expr\JoinType;

/**
 * @template X of Entity
 */
trait JoinBuildable
{
    /**
     * @template Y of Entity
     * @param JoinBuilder<X, Y> $builder
     * @param JoinType $joinType
     * @return $this
     */
    abstract function joinFromBuilder(JoinBuilder $builder, JoinType $joinType): static;

    /**
     * @param literal-string $property
     * @param ?string $alias
     * @return JoinBuilder<X, Entity>
     */
    public function createJoin(string $property, ?string $alias = null): JoinBuilder
    {
        return new JoinBuilder($property, $alias);
    }

    /**
     * @template Y of Entity
     * @param literal-string|JoinBuilder<X, Y> $property
     * @param ?string $alias
     * @param JoinType $joinType
     * @return $this
     */
    public function join(string|JoinBuilder $property, ?string $alias = null, JoinType $joinType = JoinType::INNER): static
    {
        if ($property instanceof JoinBuilder) {
            return $this->joinFromBuilder($property, $joinType);
        }
        return $this->joinFromBuilder($this->createJoin($property, $alias), $joinType);
    }

    /**
     * @template Y of Entity
     * @param literal-string|JoinBuilder<X, Y> $property
     * @param ?string $alias
     * @return $this
     */
    public function innerJoin(string|JoinBuilder $property, ?string $alias = null): static
    {
        return $this->join($property, $alias);
    }

    /**
     * @template Y of Entity
     * @param literal-string|JoinBuilder<X, Y> $property
     * @param ?string $alias
     * @return $this
     */
    public function leftJoin(string|JoinBuilder $property, ?string $alias = null): static
    {
        return $this->join($property, $alias, JoinType::LEFT);
    }

    /**
     * @template Y of Entity
     * @param literal-string|JoinBuilder<X, Y> $property
     * @param ?string $alias
     * @return $this
     */
    public function rightJoin(string|JoinBuilder $property, ?string $alias = null): static
    {
        return $this->join($property, $alias, JoinType::RIGHT);
    }
}
