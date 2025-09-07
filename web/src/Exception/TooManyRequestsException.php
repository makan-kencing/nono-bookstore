<?php

declare(strict_types=1);

namespace App\Exception;

use JsonSerializable;
use Throwable;

class TooManyRequestsException extends WebException
{
    /** The number of seconds to retry after. */
    public readonly int $retryAfter;

    public function __construct(
        int $retryAfter,
        JsonSerializable|array $details = [],
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($details, $message, 429, $previous);
        $this->retryAfter = $retryAfter;
    }

    public function setHeaders(): void
    {
        parent::setHeaders();
        header('Retry-After: ' . $this->retryAfter);
    }
}
