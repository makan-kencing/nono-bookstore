<?php

declare(strict_types=1);

namespace App\Orm;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class OneToMany
{
    /**
     * @param class-string<Entity> $targetClass
     * @param ?string $mappedBy
     * @param bool $optional
     */
    public function __construct(
        public string $targetClass,
        public ?string $mappedBy = null,
        public bool $optional = false
    ) {
    }
}
