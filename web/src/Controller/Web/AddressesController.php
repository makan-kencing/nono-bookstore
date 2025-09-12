<?php

namespace App\Controller\Web;

use App\Controller\Web\WebController;
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

#[Path('/addresses')]
#[RequireAuth]
readonly class AddressesController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET]
    public function viewAddresses(): void{
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
            'webstore/addresses.php', ['user' => $user]
        );
    }
}
