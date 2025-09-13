<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Core\View;
use App\DTO\Request\AddressCreateDTO;
use App\DTO\Request\UserAddressDTO;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Repository\UserRepository;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\UserService;
use PDO;

#[RequireAuth]
#[Path('/api/address')]
readonly class AddressController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws UnauthorizedException
     */
    #[POST]
    public function createAddress(): void
    {
        $dto = AddressCreateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->createAddress($dto);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    #[PUT]
    #[Path('/{id}')]
    public function editAddress(string $id): void
    {
        $dto = UserAddressDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->updateUserAddress($dto,$id);
    }

    /**
     * @throws ForbiddenException
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    #[DELETE]
    #[Path('/{id}/delete')]
    public function deleteAddress(string $id): void
    {
        $this->userService->deleteAddress((int) $id);
    }

    /**
     * @throws UnauthorizedException
     */
    #[PUT]
    #[Path('/{id}/default')]
    public function setDefaultAddress(string $id): void
    {
        $this->userService->setAddressDefault((int)$id);
    }
}
