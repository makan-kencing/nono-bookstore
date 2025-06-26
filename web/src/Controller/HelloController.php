<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\View;

class HelloController
{
    public function index($vars) : void
    {
        echo View::render('base.php');
    }
}