<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Core\View;
use App\DTO\Request\SearchDTO;
use App\DTO\Request\ShipmentUpdateDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\OrderService;
use App\Service\UserService;
use PDO;

#[Path('/api/orderList')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
readonly class OrderController extends ApiController
{

    private OrderService $orderService;
    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderService = new OrderService($this->pdo);
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    #[PUT]
    #[Path('/{id}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function updateShipmentStatus(string $id): void
    {
        $this->orderService->updateShipment((int)$id);
        http_response_code(204);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[GET]
    #[Path('/search/')]
    #[Path('/search/{query}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function search(?string $query = null): void
    {
        if ($query !== null)
            $_GET['query'] = $query;

        $dto = SearchDTO::jsonDeserialize($_GET);
        $dto->validate();

        $page = $this->orderService->search($dto);

        if ($_SERVER['HTTP_ACCEPT'] === 'text/html') {
            header('Content-Type: text/html');
            echo $this->render(
                'admin/_component/_orders_table.php',
                ['page' => $page, 'search' => $dto]
            );
        }
    }
}
