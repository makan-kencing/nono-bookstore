<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\ProtectedController;
use App\Core\View;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Repository\Query\QueryUserCount;
use App\Repository\UserRepository;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use PDO;

#[Path('/api/user')]
readonly class AdminUserController extends ProtectedController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[POST]
    public function addUser(): void
    {
        $_POST = self::getJsonBody();

        $query = new QueryUserCount();
        $query->username = $_POST['username'];

        if ($this->userRepository->count($query) > 0) {
            throw new ConflictException([['field' => 'username']]);
        }

        $user = new User();

        $user->username = $_POST['username'];
        $user->email = $_POST['email'];
        $user->hashedPassword = $_POST['hashed_password'];
        $user->role = UserRole::{$_POST['role']};
        $user->isVerified = $_POST['is_verified'];

        $this->userRepository->insert($user);
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

        $this->userRepository->update($user);
    }

    #[DELETE]
    public function delUser(): void
    {
    }
}
