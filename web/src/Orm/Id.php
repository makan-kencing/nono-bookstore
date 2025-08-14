<?php

declare(strict_types=1);

namespace App\Orm;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Id
{
}
