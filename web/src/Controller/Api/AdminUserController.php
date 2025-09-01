<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\UserCreateDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\UserService;
use PDO;

#[Path('/api/user')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER)]
readonly class AdminUserController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    #[POST]
    public function addUser(): void
    {
        $dto = UserCreateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->create($dto);

    }

    #[PUT]
    public function editUser(): void
    {
        $_PUT = self::getJsonBody();

        $user = new User();

        $user->username = $_PUT['username'];
        $user->email = $_PUT['email'];
        $user->hashedPassword = $_PUT['hashed_password'];
        $user->role = UserRole::{$_PUT['role']};
        $user->isVerified = $_PUT['is_verified'];

        $this->userService->update($user);
    }

    #[DELETE]
    public function delUser(): void
    {
    }
}
