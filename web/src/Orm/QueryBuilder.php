<?php

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Expr\JoinType;
use App\Orm\Expr\OrderBy;
use App\Orm\Expr\OrderDirection;
use App\Orm\Expr\PageRequest;
use App\Orm\Expr\Predicate;
use App\Orm\Expr\Root;

/**
 * @template X of Entity
 * @phpstan-type Property string
 */
class QueryBuilder
{
    /** @use JoinBuildable<X> */
    use JoinBuildable;

    /** @var ?Root<X> */
    private ?Root $root = null;

    private ?Predicate $where = null;

    /** @var array<literal-string, OrderBy> */
    private array $orderBys = [];

    /** @var array<literal-string, mixed> */
    private array $parameters = [];

    private ?PageRequest $pageRequest = null;


    /**
     * @return ?class-string<X>
     */
    public function getClass(): ?string
    {
        return $this->root?->class;
    }

    /**
     * @return array<literal-string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function createPredicate(string $predicate): Predicate
    {
        return new Predicate($predicate);
    }

    /**
     * @param class-string<X> $class
     * @param ?string $alias
     * @return $this
     * @phpstan-self-out self<X>
     */
    public function from(string $class, ?string $alias = null): static
    {
        $this->root = new Root($class, $alias);
        return $this;
    }

    /**
     * @inheritDoc
     */
    private function joinFromBuilder(JoinBuilder $builder, JoinType $joinType): static
    {
        $builder->joinType = $joinType;
        $builder->build($this->root);
        return $this;
    }

    /**
     * @param string|Predicate $predicate
     * @return $this
     */
    public function where(string|Predicate $predicate): static
    {
        if (is_string($predicate))
            $predicate = $this->createPredicate($predicate);
        $this->where = $predicate;
        return $this;
    }

    /**
     * @param literal-string $property
     * @param OrderDirection $direction
     * @return $this
     */
    public function orderBy(string $property, OrderDirection $direction = OrderDirection::ASCENDING): static
    {
        $this->orderBys[$property] = new OrderBy($property, $direction);
        return $this;
    }

    public function page(PageRequest $pageRequest): static
    {
        $this->pageRequest = $pageRequest;
        return $this;
    }

    /**
     * @param literal-string $param
     * @param mixed $value
     * @return $this
     * @phpstan-impure
     */
    public function bind(string $param, mixed $value): static
    {
        $this->parameters[$param] = $value;
        return $this;
    }

    /**
     * @return string[]
     */
    private function getSqlSelectClauses(): array
    {
        if (!$this->root)
            return [];
        return $this->root->toSelectClauses();
    }

    private function getSqlFromClause(): string
    {
        if (!$this->root)
            return '';
        return $this->root->toFromClause();
    }

    /**
     * @return string[]
     */
    private function getSqlJoinClauses(): array
    {
        if (!$this->root)
            return [];
        return $this->root->toJoinClauses();
    }

    /**
     * @return string[]
     */
    private function getSqlWhereClauses(): array
    {
        if (!$this->where)
            return [];
        return $this->where->toClauses();
    }

    private function getSqlOrderByClause(): string
    {
        if (!$this->orderBys)
            return '';
        return 'ORDER BY ' . implode(
                ', ',
                array_map(fn(OrderBy $orderBy) => $orderBy->toClause(), $this->orderBys)
            );
    }

    private function getSqlLimitOffsetClause(): string
    {
        if ($this->pageRequest) {
            return 'LIMIT ' . $this->pageRequest->size . ' '
                . 'OFFSET ' . (($this->pageRequest->page - 1) * $this->pageRequest->size);
        }
        return '';
    }

    public function getQuery(): string
    {
        $query = "SELECT\n"
            . implode(",\n", $this->getSqlSelectClauses()) . "\n"
            . 'FROM ' . $this->getSqlFromClause() . "\n"
            . implode("\n", $this->getSqlJoinClauses());

        if ($whereParts = $this->getSqlWhereClauses()) {
            $query .= "\nWHERE " . implode("\n", $whereParts);
        }

        if ($orderByPart = $this->getSqlOrderByClause()) {
            $query .= "\n" . $orderByPart;
        }

        if ($offsetPart = $this->getSqlLimitOffsetClause()) {
            $query .= "\n" . $offsetPart;
        }

        return $query . ';';
    }

    public function getCount(): string
    {
        $query = "SELECT\n"
            . "COUNT(*)\n"
            . 'FROM ' . $this->getSqlFromClause() . "\n"
            . implode("\n", $this->getSqlJoinClauses()) . "\n";

        if ($whereParts = $this->getSqlWhereClauses()) {
            $query .= 'WHERE ' . implode("\n", $whereParts) . "\n";
        }

        return $query . ';';
    }
}
