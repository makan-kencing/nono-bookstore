<?php

declare(strict_types=1);

namespace App\Orm\Expr;

class OrderBy
{
    public readonly string $property;
    public readonly OrderDirection $direction;

    public function __construct(string $property, OrderDirection $direction = OrderDirection::ASCENDING)
    {
        $this->property = $property;
        $this->direction = $direction;
    }

    public function reversed(): OrderBy
    {
        return new self($this->property, $this->direction->reversed());
    }

    public function toClause(): string
    {
        return $this->property . ' ' . ($this->direction == OrderDirection::ASCENDING ? 'ASC' : 'DESC');
    }
}
