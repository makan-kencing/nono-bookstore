<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use PDO;

abstract class Controller
{
    protected readonly PDO $pdo;
    protected readonly View $view;

    public function __construct(PDO $pdo, View $view)
    {
        $this->pdo = $pdo;
        $this->view = $view;
    }

    public function render(string $file, array $data = []): string
    {
        return $this->view->render($file, $data);
    }
}
