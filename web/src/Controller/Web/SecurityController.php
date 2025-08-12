<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

readonly class SecurityController extends WebController
{
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
}
