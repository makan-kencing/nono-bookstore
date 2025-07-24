<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Repository\Query\QueryUserListing;
use App\Repository\UserRepository;
use PDO;

readonly class AdminUserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    public function viewUserList(): void
    {
        $query = new QueryUserListing();

        $users = $this->userRepository->get($query);

        // convert to dto

        echo $this->render('admin/users.php', ['users' => $users]);
    }

    public function viewUserDetails(): void
    {
        $query = new QueryUserDetails();

        $users = $this->userRepository->get($query);
    }

    public function addStaff($vars): void
    {

    }

    public function delStaff($vars): void
    {

    }

    public function editUser($vars): void
    {

    }

    public function addMember($vars): void
    {

    }

    public function delMember($vars): void
    {

    }
}
