<?php

declare(strict_types=1);

namespace App\Orm\Attribute;

use App\Orm\Entity;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ManyToOne
{
    /**
     * @param ?class-string<Entity> $targetClass
     * @param ?bool $optional
     */
    public function __construct(
        public ?string $targetClass = null,
        public ?bool $optional = null
    ) {
    }
}
