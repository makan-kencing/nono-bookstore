<?php

declare(strict_types=1);

namespace App\Utils;

trait EnumUtils
{
    public function title(): string
    {
        return ucwords(strtolower($this->name));
    }

    public function fromName(string $name): self
    {
        return constant("self::$name");
    }
}
