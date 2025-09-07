<?php

declare(strict_types=1);

namespace App\Exception;

use JsonSerializable;
use Throwable;

class MethodNotAllowedException extends WebException
{
    /** @var string[] */
    public readonly array $allowedMethods;

    /**
     * @param string[] $allowedMethods
     * @param JsonSerializable|array $details
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        array $allowedMethods,
        JsonSerializable|array $details = [],
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($details, $message, 405, $previous);
        $this->allowedMethods = $allowedMethods;
    }

    public function setHeaders(): void
    {
        parent::setHeaders();
        header('Allow: ' . implode(', ', $this->allowedMethods));
    }
}
