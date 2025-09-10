<?php

declare(strict_types=1);

namespace App\Exception;

use JsonSerializable;
use Throwable;

class PaymentRequiredException extends WebException
{
    public readonly string $redirectTo;

    public function __construct(
        string $redirectTo,
        JsonSerializable|array $details = [],
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($details, $message, 402, $previous);
        $this->redirectTo = $redirectTo;
    }
}
