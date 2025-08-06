<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\Web\WebController;
use App\Router\Method\GET;
use App\Router\Path;

readonly class AdminDashboardController extends WebController
{
    #[GET]
    #[Path('/admin')]
    public function viewDashboard(): void
    {
        echo $this->render('admin/dashboard.php');
    }
}
