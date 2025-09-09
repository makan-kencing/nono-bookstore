<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Porting of PHP 8.4 function
 *
 * @template TValue of mixed
 * @template TKey of array-key
 *
 * @param array<TKey, TValue> $array
 * @param callable(TValue $value, TKey $key): bool $callback
 * @return ?TValue
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
function array_find(array $array, callable $callback): mixed
{
    foreach ($array as $key => $value)
        if ($callback($value, $key))
            return $value;
    return null;
}
