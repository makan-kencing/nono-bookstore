<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\DTO\UserLoginContextDTO;
use App\Service\AuthService;
use PDO;

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

    public function getSessionContext(): ?UserLoginContextDTO
    {
        return AuthService::getLoginContext();
    }
}
