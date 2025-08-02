<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Repository\Query\QueryUserListing;
use App\Repository\UserRepository;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/admin/user')]
readonly class AdminUserController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET]
    public function viewUserList(): void
    {
        $query = new QueryUserListing();

        $users = $this->userRepository->get($query);

        // convert to dto

        echo $this->render('admin/users.php', ['users' => $users]);
    }

    #[GET]
    #[Path('/{id}')]
    public function viewUserDetails(string $id): void
    {
    }
}
