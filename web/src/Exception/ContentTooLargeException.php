<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class ContentTooLargeException extends WebException
{
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 413, $previous);
    }
}
