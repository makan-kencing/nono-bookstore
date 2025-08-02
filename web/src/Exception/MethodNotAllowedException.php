<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class MethodNotAllowedException extends WebException
{
    /** @var string[] */
    public readonly array $allowedMethods;

    /**
     * @param string[] $allowedMethods
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(array $allowedMethods, string $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, 405, $previous);
        $this->allowedMethods = $allowedMethods;
    }
}
