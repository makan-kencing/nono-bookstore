<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class BadRequestException extends WebException
{
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
