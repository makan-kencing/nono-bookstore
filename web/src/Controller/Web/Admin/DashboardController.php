<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/admin')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class DashboardController extends WebController
{
    #[GET]
    public function viewDashboard(): void
    {
        echo $this->render('admin/dashboard.php');
    }
}
