<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use JsonSerializable;
use Throwable;

abstract class WebException extends Exception implements JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        if (is_array($this->details))
            return $this->details;
        return $this->details->jsonSerialize();
    }

    public function setHeaders(): void
    {
    }
}
