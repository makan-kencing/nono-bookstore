<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\AuthRule;
use App\Controller\Web\RequireAuth;
use App\Controller\Web\UserRole;
use App\Controller\Web\WebController;
use App\Core\View;
use App\Exception\NotFoundException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/admin/user')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class UserController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    /**
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/{id}')]
    public function viewProfile(string $id): void
    {
        $qb = UserQuery::userListings()
            ->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        $user = $this->userRepository->getOne($qb);

        if ($user === null)
            throw new NotFoundException();

        echo $this->render('admin/user/user.php', ['user' => $user]);
    }

    #[GET]
    #[Path('/{id}/addresses')]
    public function viewUserAddresses(string $id): void
    {
        $qb = UserQuery::userListings()
            ->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        $user = $this->userRepository->getOne($qb);

        if ($user === null)
            throw new NotFoundException();

        echo $this->render('admin/user/addresses.php', ['user' => $user]);
    }

    #[GET]
    #[Path('/{id}/orders')]
    public function viewUserOrders(string $id): void
    {
        $qb = UserQuery::userListings()
            ->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        $user = $this->userRepository->getOne($qb);

        if ($user === null)
            throw new NotFoundException();

        echo $this->render('admin/user/orders.php', ['user' => $user]);
    }
}
