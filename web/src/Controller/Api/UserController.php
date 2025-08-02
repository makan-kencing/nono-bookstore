<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Core\View;
use App\Repository\Query\QueryUserCount;
use App\Repository\UserRepository;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/api/user')]
readonly class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET]
    #[Path('/username/{username}')]
    public function checkUsernameExists(string $username): void
    {
        $query = new QueryUserCount();
        $query->username = $username;

        $count = $this->userRepository->count($query);

        header('Content-type: application/json');
        echo json_encode(['exists' => $count != 0]);
    }
}
