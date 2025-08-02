<?php

declare(strict_types=1);

namespace App\Router\Method;

enum HttpMethod: string
{
    case NOOP = '';
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
