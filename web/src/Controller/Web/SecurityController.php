<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\AuthService;
use PDO;

readonly class SecurityController extends WebController
{
    private AuthService $authService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->authService = new AuthService($this->pdo);
    }

    #[GET]
    #[Path('/login')]
    public function login(): void
    {
        echo $this->render('auth/login.php');
    }

    #[GET]
    #[Path('/register')]
    public function register(): void
    {
        echo $this->render('auth/register.php');
    }

    #[GET]
    #[Path('/logout')]
    public function logout(): void
    {
        $this->authService->logout();

        http_response_code(303);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
