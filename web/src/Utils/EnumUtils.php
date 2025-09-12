<?php

declare(strict_types=1);

namespace App\Utils;

use Throwable;

trait EnumUtils
{
    public function title(): string
    {
        return ucwords(strtolower($this->name));
    }

    public function compareTo(self $other): int
    {
        return $this->value - $other->value;
    }

    public static function fromName(string $name): self
    {
        return constant("self::$name");
    }

    public static function tryFromName(?string $name): ?self
    {
        try {
            return self::fromName($name);
        } catch (Throwable) {
            return null;
        }
    }

    public static function comparing(self $o1, self $o2): int
    {
        return $o1->compareTo($o2);
    }
}
