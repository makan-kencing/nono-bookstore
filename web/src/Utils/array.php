<?php

declare(strict_types=1);

namespace App\Utils;

use UnexpectedValueException;

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
 *
 * @param array<TKey, TValue> $array
 * @param callable(TValue $value, TKey $key): bool $callback
 * @return ?TKey
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
function array_find_key(array $array, callable $callback): string|int|null
{
    foreach ($array as $key => $value)
        if ($callback($value, $key))
            return $key;
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

/**
 * Reposition an array element by its key.
 *
 * @template TValue of mixed
 * @template TKey of array-key
 *
 * @param array<TKey, TValue> $array The array being reordered.
 * @param TKey $key They key of the element you want to reposition.
 * @param int $order The position in the array you want to move the element to. (0 is first)
 */
// https://stackoverflow.com/questions/12624153/move-an-array-element-to-a-new-index-in-php
function array_move_elem(array &$array, int|string $key, int $order): void
{
    if (($a = array_search($key, array_keys($array))) === false)
        throw new UnexpectedValueException("The {$key} cannot be found in the given array.");
    $p1 = array_splice($array, $a, 1);
    $p2 = array_splice($array, 0, $order);
    $array = array_merge($p2, $p1, $array);
}
