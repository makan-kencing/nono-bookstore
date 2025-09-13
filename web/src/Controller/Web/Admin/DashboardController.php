<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Core\View;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\OrderService;
use DateTime;
use PDO;

#[Path('/admin')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class DashboardController extends WebController
{
    private OrderService $orderService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderService = new OrderService($pdo);
    }

    #[GET]
    public function viewDashboard(): void
    {
        $from = new DateTime('2000-01-01');
        $to = new DateTime('2100-01-01');
        list($monthlySales, $categorySales) = $this->orderService->getSalesMetrics($from, $to);

        echo $this->render('admin/dashboard.php', ['monthlySales' => $monthlySales, 'categorySales' => $categorySales]);
    }
}
