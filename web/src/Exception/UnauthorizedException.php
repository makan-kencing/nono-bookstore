<?php

declare(strict_types=1);

namespace App\Exception;

use JsonSerializable;
use Throwable;

class UnauthorizedException extends WebException
{
    public function __construct(
        JsonSerializable|array $details = [],
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($details, $message, 401, $previous);
    }
}
