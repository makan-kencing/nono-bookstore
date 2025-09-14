<?php

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/about')]
readonly class AboutMeController extends WebController
{
    #[GET]
    public function viewAbout(): void
    {
        echo $this->render('webstore/about.php');
    }
}
