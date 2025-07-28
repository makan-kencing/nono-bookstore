<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Repository\Query\QueryUserCount;
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
    }

    public function checkUsernameExists(): void
    {
        $query = new QueryUserCount();
        $query->username = $_GET['username'];

        $count = $this->userRepository->count($query);

        header('Content-type: application/json');
        echo json_encode(['exists' => $count != 0]);
    }

    public function addUser(): void
    {
        $_POST = json_decode(stream_get_contents(fopen('php://input', 'r')), true);

        $query = new QueryUserCount();
        $query->username = $_POST['username'];

        if ($this->userRepository->count($query) > 0) {
            throw new ApiExceptionWrapper(new ConflictException([['field' => 'username']]));
        }

        $user = new User();

        $user->username = $_POST['username'];
        $user->email = $_POST['email'];
        $user->hashedPassword = $_POST['hashed_password'];
        $user->role = UserRole::{$_POST['role']};
        $user->isVerified = $_POST['is_verified'];

        $this->userRepository->insert($user);

    }

    public function editUser(): void
    {
        $_PUT = json_decode(stream_get_contents(fopen('php://input', 'r')), true);

        $user = new User();

        $user->username=$_PUT['username'];
        $user->email = $_PUT['email'];
        $user->hashedPassword = $_PUT['hashed_password'];
        $user->role = UserRole::{$_PUT['role']};
        $user->isVerified = $_PUT['is_verified'];

        $this->userRepository->update($user);
    }

    public function delUser($vars): void
    {

    }



    public function addMember($vars): void
    {

    }

    public function delMember($vars): void
    {

    }
}
