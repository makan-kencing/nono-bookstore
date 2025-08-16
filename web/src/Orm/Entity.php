<?php

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Attribute\Transient;

abstract class Entity
{
    #[Transient]
    public bool $isLazy = true;
}
