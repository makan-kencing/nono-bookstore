<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/')]
readonly class HomeController extends Controller
{
    #[GET]
    public function index(): void
    {
        echo $this->render('home.php', [
            'home' => 'wahahaha'
        ]);
    }
}
