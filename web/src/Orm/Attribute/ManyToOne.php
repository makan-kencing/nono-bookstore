<?php

declare(strict_types=1);

namespace App\Orm\Attribute;

use App\Orm\Entity;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToOne
{
    public function __construct() {
    }
}
