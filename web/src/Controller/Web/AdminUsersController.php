<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Entity\User\UserRole;
use App\Orm\Expr\PageRequest;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/admin/users')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class AdminUsersController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET] //This is for database using
    public function viewUserList(): void
    {
        $qb = UserQuery::userListings();
        $qb->page(new PageRequest(3, 10));

        $users = $this->userRepository->get($qb);

        // convert to dto

        echo $this->render('admin/users.php', ['users' => $users]);
    }

//    #[GET]
//    #[Path('/{id}')]
//    public function viewUserDetails(string $id): void
//    {
//    }
}
