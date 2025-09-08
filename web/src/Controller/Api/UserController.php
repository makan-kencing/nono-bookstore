<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\UserService;
use PDO;

#[Path('/api/user')]
readonly class UserController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    #[GET]
    #[Path('/username/{username}')]
    public function checkUsernameExists(string $username): void
    {
        echo json_encode(['exists' => $this->userService->checkUsernameExists($username)]);
    }

    #[GET]
    #[Path('/email/{email}')]
    public function checkEmailExists(string $email): void
    {
        echo json_encode(['exists' => $this->userService->checkEmailExists($email)]);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ConflictException
     */
    #[POST]
    #[Path('/register')]
    public function register(): void
    {

        $dto = UserRegisterDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->register($dto);

        http_response_code(201);
    }

    /**
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     */
    #[POST]
    #[Path('/login')]
    public function login(): void
    {
        $dto = UserLoginDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        if ($this->userService->login($dto))
            http_response_code(200);
        else
            http_response_code(401);
    }

    #[POST]
    #[Path('/logout')]
    #[RequireAuth(redirect: false)]
    public function logout(): void
    {
        $this->userService->logout();

        http_response_code(204);
    }
}
