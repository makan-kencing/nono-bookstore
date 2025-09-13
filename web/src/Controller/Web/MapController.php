<?php

namespace App\Controller\Web;

use App\Controller\Web\WebController;
use App\Core\View;
use App\Repository\OrderRepository;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/map')]
#[RequireAuth]
readonly class MapController extends WebController
{
    #[GET]
    public function viewMap(): void
    {
        echo $this->render('webstore/map.php');
    }
}
