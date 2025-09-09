<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Porting of PHP 8.4 function
 *
 * @template TValue of mixed
 * @template TKey of array-key
 * @template default
 *
 * @param array<TKey, TValue> $array
 * @param TKey $key
 * @param default $default
 * @return TValue|default
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
function array_get(array $array, int|string $key, $default = null): mixed
{
    if (array_key_exists($key, $array))
        return $array[$key];
    return $default;
}
