<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UnauthorizedException extends WebException
{
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}
