<?php

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Expr\From;
use App\Orm\Expr\JoinType;

/**
 * @template Z of Entity
 * @template X of Entity
 */
class JoinBuilder
{
    /** @use JoinBuildable<X> */
    use JoinBuildable;

    /** @var literal-string */
    public readonly string $property;
    public readonly ?string $alias;
    public ?JoinType $joinType;

    /**
     * @param literal-string $property
     * @param ?string $alias
     * @param ?JoinType $joinType
     */
    public function __construct(
        string $property,
        ?string $alias = null,
        ?JoinType $joinType = null
    ) {
        $this->property = $property;
        $this->alias = $alias;
        $this->joinType = $joinType;
    }


    /** @var array<string, JoinBuilder<X, Entity>> */
    public array $joins = [];

    /**
     * @template Y of Entity
     * @param JoinBuilder<X, Y> $builder
     * @param JoinType $joinType
     * @return $this
     */
    private function joinFromBuilder(JoinBuilder $builder, JoinType $joinType): static
    {
        $builder->joinType = $joinType;
        $this->joins[$builder->property] = $builder;
        return $this;
    }

    /**
     * @param From<Z, X> $from
     * @return void
     */
    public function build(From $from): void
    {
        assert($this->joinType != null);
        $join = $from->join($this->property, $this->alias, $this->joinType);
        foreach ($this->joins as $builder) {
            // if this is left, make all nested joins left
            if ($this->joinType == JoinType::LEFT)
                $builder->joinType = JoinType::LEFT;

            $builder->build($join);
        }
    }
}
