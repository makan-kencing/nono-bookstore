<?php

declare(strict_types=1);

namespace App\Orm\Expr;

class Predicate
{
    public readonly string|Predicate $left;
    public PredicateOperator $operator;
    public string|Predicate|null $right;

    public function __construct(
        string|Predicate $left,
        string|Predicate|null $right = null,
        PredicateOperator $operator = PredicateOperator::AND
    ) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }

    public function isCombined(): bool
    {
        return $this->right != null;
    }

    /**
     * @param string|Predicate $operation
     * @param PredicateOperator $operator
     * @return $this
     */
    public function combine(string|Predicate $operation, PredicateOperator $operator): static
    {
        // if is already a combined predicate
        if ($this->isCombined())
            return new Predicate($this, $operation, $operator);

        $this->right = $operation;
        $this->operator = $operator;
        return $this;
    }

    public function and(string|Predicate $operation): static
    {
        return $this->combine($operation, PredicateOperator::AND);
    }

    public function or(string|Predicate $operation): static
    {
        return $this->combine($operation, PredicateOperator::OR);
    }

    /**
     * @return string[]
     */
    public function toClauses(): array
    {
        /** @var string[] $clauses */
        $clauses = [];

        if ($this->left instanceof Predicate)
            foreach ($this->left->toClauses() as $clause)
                $clauses[] = $clause;
        else
            $clauses[] = $this->left;

        if ($this->isCombined()) {
            $clauses[] = $this->operator->name;

            if ($this->right instanceof Predicate)
                foreach ($this->right->toClauses() as $clause)
                    $clauses[] = $clause;
            else
                $clauses[] = $this->right;
        }
        return $clauses;
    }
}

