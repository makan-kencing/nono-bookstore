<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Core\View;
use JsonSerializable;
use PDO;
use RuntimeException;

const INPUT = 'php://input';

abstract readonly class ApiController extends Controller
{
    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        header('Content-Type: application/json');
    }

    public static function getJsonBody(): mixed
    {
        $resource = fopen(INPUT, 'r');
        if (!$resource) {
            throw new RuntimeException(INPUT . ' stream cant be opened.');
        }

        return json_decode(stream_get_contents($resource), true);
    }
}
