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
        header('Content-Type: application/json');
        echo json_encode(['exists' => $this->userService->checkUsernameExists($username)]);
    }

    #[GET]
    #[Path('/email/{email}')]
    public function checkEmailExists(string $email): void
    {
        header('Content-Type: application/json');
        echo json_encode(['exists' => $this->userService->checkEmailExists($email)]);
    }
}
