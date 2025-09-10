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
 * @return bool
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
function array_all(array $array, callable $callback): bool
{
    foreach ($array as $key => $value)
        if (!$callback($value, $key))
            return false;
    return true;
}

/**
 * Porting of PHP 8.4 function
 *
 * @template TValue of mixed
 * @template TKey of array-key
 *
 * @param array<TKey, TValue> $array
 * @param callable(TValue $value, TKey $key): bool $callback
 * @return bool
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
function array_any(array $array, callable $callback): bool
{
    foreach ($array as $key => $value)
        if ($callback($value, $key))
            return true;
    return false;
}

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

/**
 * @template K of array-key
 * @template V
 *
 * @param $array V[]
 * @param $keyExtractor callable(V $V): K
 * @return array<K, V[]>
 */
function array_group_by(array $array, callable $keyExtractor): array
{
    $grouped = [];
    foreach ($array as $value) {
        $key = $keyExtractor($value);

        $grouped[$key] ??= [];
        $grouped[$key][] = $value;
    }

    return $grouped;
}
