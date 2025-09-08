<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\UserCreateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\UserService;
use PDO;

#[Path('/api/user')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
readonly class AdminUserController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    /**
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     */
    #[POST]
    public function addUser(): void
    {
        $dto = UserCreateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->create($dto);

        http_response_code(201);
    }

    /**
     * @throws ForbiddenException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    #[PUT]
    public function editUser(): void
    {
        $dto = UserUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->update($dto);

        http_response_code(204);
    }

    #[DELETE]
    #[Path('/{id}')]
    public function delUser(string $id): void
    {
        $this->userService->delete((int) $id);

        http_response_code(204);
    }
}
