<?php

declare(strict_types=1);

namespace App\Controller;

readonly class HomeController extends Controller
{
    public function index(): void
    {
        echo $this->render('home.php', [
            'home' => 'wahahaha'
        ]);
    }
}
