<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Service\SecurityService;

readonly class SecurityController extends Controller
{
    public function register(): void
    {
        echo $this->render('register.php');
    }
}
