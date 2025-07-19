<?php

declare(strict_types=1);

namespace App\Exception\Wrapper;

use Exception;
use Throwable;

/**
 * @template T of Throwable
 * @mixin T
 */
abstract class ExceptionWrapper extends Exception
{
    public readonly Throwable $original;

    /**
     * @param T $original
     */
    public function __construct($original, string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, $original->getCode(), $previous);
        $this->original = $original;
    }
}
