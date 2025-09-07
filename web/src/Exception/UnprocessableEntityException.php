<?php

declare(strict_types=1);

namespace App\Exception;

use JsonSerializable;
use Throwable;

class UnprocessableEntityException extends WebException
{
    public function __construct(
        JsonSerializable|array $details = [],
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($details, $message, 422, $previous);
    }
}
