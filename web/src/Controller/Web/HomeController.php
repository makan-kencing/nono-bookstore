<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/')]
readonly class HomeController extends WebController
{
    #[GET]
    public function index(): void
    {
        echo $this->render('webstore/home.php', [
            'home' => 'wahahaha'
        ]);
    }
}
