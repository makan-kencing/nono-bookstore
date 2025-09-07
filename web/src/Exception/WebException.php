<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use JsonSerializable;
use Throwable;

abstract class WebException extends Exception
{
    public readonly JsonSerializable|array $details;

    public function __construct(
        JsonSerializable|array $details = [],
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    public function setHeaders(): void
    {
    }
}
