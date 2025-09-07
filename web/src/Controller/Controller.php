<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use PDO;
use RuntimeException;

const INPUT = 'php://input';

abstract readonly class Controller
{
    protected PDO $pdo;
    protected View $view;

    public function __construct(PDO $pdo, View $view)
    {
        $this->pdo = $pdo;
        $this->view = $view;
    }

    /**
     * @param string $file
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(string $file, array $data = []): string
    {
        return $this->view->render($file, $data);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
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
