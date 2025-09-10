<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Core\View;
use App\DTO\Request\PaginationDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/admin/users')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class UsersController extends WebController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[GET] //This is for database using
    public function viewUserList(): void
    {
        $dto = PaginationDTO::jsonDeserialize($_GET);
        $dto->validate();

        $pageRequest = $dto->toPageRequest();

        $qb = UserQuery::userListings();
        $qb->page($pageRequest);
        $users = $this->userRepository->get($qb);
        $count = $this->userRepository->count($qb);

        // convert to dto

        echo $this->render(
            'admin/users.php',
            ['users' => $users, 'page' => $pageRequest, 'count' => $count]
        );
    }

//    #[GET]
//    #[Path('/{id}')]
//    public function viewUserDetails(string $id): void
//    {
//    }
}
