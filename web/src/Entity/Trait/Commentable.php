<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Orm\Entity;

/**
 * @phpstan-require-extends Entity
 */
trait Commentable
{
    public ?string $comment;
}
