<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UnprocessableEntityException extends WebException
{
    /** @var list<array{field: string, type: string, reason: string}> */
    public readonly array $details;

    /**
     * @param list<array{field: string, type: string, reason: string}> $details
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(array $details, string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
        $this->details = $details;
    }
}
