<?php

declare(strict_types=1);

namespace App\Exception\Wrapper;

use App\Exception\WebException;
use Exception;
use Throwable;

abstract class ExceptionWrapper extends Exception
{
    public readonly Throwable $original;

    /**
     * @param WebException $original
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(WebException $original, string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, $original->getCode(), $previous);
        $this->original = $original;
    }
}
