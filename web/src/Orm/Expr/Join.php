<?php

declare(strict_types=1);

namespace App\Orm\Expr;

use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;

/**
 * @template Z of Entity The source type
 * @template X of Entity The target type
 * @extends From<Z, X>
 */
class Join extends From
{
    public JoinType $joinType;
    public ?string $mappedBy;

    /**
     * @param class-string<X> $class
     * @param ?string $alias
     * @param JoinType $joinType
     * @param ?string $mappedBy
     */
    public function __construct(
        string $class,
        ?string $alias = null,
        JoinType $joinType = JoinType::INNER,
        ?string $mappedBy = null
    )
    {
        parent::__construct($class, $alias);
        $this->joinType = $joinType;
        $this->mappedBy = $mappedBy;
    }

    /**
     * @param ReflectionProperty $reflectionProperty
     * @param ?string $alias
     * @param JoinType $joinType
     * @return Join<Z, X>
     */
    public static function fromProperty(
        ReflectionProperty $reflectionProperty,
        ?string $alias = null,
        JoinType $joinType = JoinType::INNER
    ): Join
    {
        if ($oneToOne = $reflectionProperty->getAttributes(OneToOne::class)) {
            assert(count($oneToOne) == 1);
            $oneToOne = $oneToOne[0]->newInstance();

            $reflectionType = $reflectionProperty->getType();
            assert($reflectionType != null);

            $class = $reflectionType->getName();
            return new self($class, $alias, $joinType, $oneToOne->mappedBy);
        } else if ($manyToOne = $reflectionProperty->getAttributes(ManyToOne::class)) {
            assert(count($manyToOne) == 1);

            $reflectionType = $reflectionProperty->getType();
            assert($reflectionType != null);

            $class = $reflectionType->getName();
            return new self($class, $alias, $joinType);
        } else if ($oneToMany = $reflectionProperty->getAttributes(OneToMany::class)) {
            assert(count($oneToMany) == 1);
            $oneToMany = $oneToMany[0]->newInstance();

            return new self($oneToMany->targetClass, $alias, $joinType, $oneToMany->mappedBy);
        }
        throw new InvalidArgumentException(
            $reflectionProperty->getDeclaringClass()->getName() . '::' . $reflectionProperty->getName()
            . ' has no relationships.'
        );
    }

    /**
     * @param class-string<Z> $class
     * @param literal-string $property
     * @param ?string $alias
     * @param JoinType $joinType
     * @return Join<Z, X>
     */
    public static function fromClass(
        string $class,
        string $property,
        ?string $alias = null,
        JoinType $joinType = JoinType::INNER
    ): Join {
        try {
            $reflectionClass = new ReflectionClass($class);
            $reflectionProperty = $reflectionClass->getProperty($property);
        } catch (ReflectionException) {
            throw new RuntimeException();
        }
        return self::fromProperty($reflectionProperty, $alias, $joinType);
    }
}
