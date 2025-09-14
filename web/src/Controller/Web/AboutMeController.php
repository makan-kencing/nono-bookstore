<?php

namespace App\Controller\Web;

use App\Controller\Web\WebController;
use App\Router\Method\GET;
use App\Router\Path;

#[Path('/about')]
readonly class AboutMeController extends WebController
{
    #[GET]
    public function viewMap(): void
    {

        echo $this->render('webstore/aboutMe.php');
    }
}
