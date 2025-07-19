<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
