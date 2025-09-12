<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Core\View;
use App\DTO\Request\ShipmentUpdateDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\OrderService;
use App\Service\UserService;
use PDO;

#[Path('/api/orderList')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
readonly class AdminOrderController extends ApiController
{

    private OrderService $orderService;
    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderService = new OrderService($this->pdo);
    }

    /**
     * @throws ForbiddenException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    #[PUT]
    #[Path('/{id}')]
    public function updateShipmentStatus(string $id): void
    {
        $this->orderService->updateShipment((int)$id);
        http_response_code(204);
    }
}
