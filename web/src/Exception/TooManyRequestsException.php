<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class TooManyRequestsException extends Exception
{
    /** The number of seconds to retry after. */
    public readonly int $retryAfter;

    /**
     * @param int $retryAfter The number of seconds to retry after.
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(int $retryAfter, string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 429, $previous);
        $this->retryAfter = $retryAfter;
    }
}
