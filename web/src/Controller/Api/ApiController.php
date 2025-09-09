<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Controller;
use RuntimeException;

const INPUT = 'php://input';

abstract readonly class ApiController extends Controller
{
    public static function getJsonBody(): mixed
    {
        $resource = fopen(INPUT, 'r');
        if (!$resource) {
            throw new RuntimeException(INPUT . ' stream cant be opened.');
        }

        return json_decode(stream_get_contents($resource), true);
    }
}
