<?php

declare(strict_types=1);

namespace App\Router;

use App\Router\Method\HttpMethod;
use Exception;
use Throwable;

class RouteInUseException extends Exception
{
    public function __construct(
        public HttpMethod $method,
        public string $path,
        public string $inUsedBy,
        public string $duplicateMethod,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $method->value . ' ' . $path . ' is already in used by ' . $this->inUsedBy
            . '. Duplicated by ' . $this->duplicateMethod,
            $code,
            $previous
        );
    }
}
