<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/account')]
#[RequireAuth]
readonly class AccountController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET]
    public function viewAccount(): void
    {
        $this->redirect('/account/profile');
//        echo $this->render('webstore/account/account.php');
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    #[GET]
    #[Path('/profile')]
    public function viewProfile(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = UserQuery::userListings()
            ->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $context->id);
        $user = $this->userRepository->getOne($qb);

        if ($user === null)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/profile.php',
            ['user' => $user]
        );
    }

    #[GET]
    #[Path('/update-password')]
    public function updatePassword(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = UserQuery::userListings()
            ->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $context->id);
        $user = $this->userRepository->getOne($qb);

        if ($user === null)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/updatePassword.php',
            ['user' => $user]
        );
    }
}
