<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\DTO\UserLoginContextDTO;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\FileService;
use PDO;

/**
 * @phpstan-import-type PhpFile from FileService
 */
abstract readonly class Controller
{
    protected PDO $pdo;
    protected View $view;
    protected AuthService $authService;

    public function __construct(PDO $pdo, View $view)
    {
        $this->pdo = $pdo;
        $this->view = $view;
        $this->authService = new AuthService($pdo);
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

    public function refreshUserContext(): ?UserLoginContextDTO
    {
        return $this->authService->refreshUserContext();
    }

    /**
     * @param array $files
     * @return PhpFile[]
     */
    function normalizeFiles(array $files): array
    {
        $isMulti = is_array($files['name']);
        $n = $isMulti ? count($files['name']) : 1;

        $normalized = [];
        for ($i = 0; $i < $n; $i++)
            foreach (array_keys($files) as $key)
                if ($isMulti)
                    $normalized[$i][$key] = $files[$key][$i];
                else
                    $normalized[$i][$key] = $files[$key];

        return $normalized;
    }
}
