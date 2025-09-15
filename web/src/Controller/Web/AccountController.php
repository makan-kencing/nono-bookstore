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
use App\Service\UserService;
use PDO;

#[RequireAuth]
#[Path('/account')]
readonly class AccountController extends WebController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    #[GET]
    public function viewProfile(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->userService->getUserForProfile($context->id);
        if ($user === null)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/account.php',
            ['user' => $user]
        );
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/change-password')]
    public function updatePassword(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->userService->getPlainUser($context->id);
        if ($user === null)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/change_password.php',
            ['user' => $user]
        );
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/addresses')]
    public function viewAddresses(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->userService->getUserForAddressesBook($context->id);
        if ($user === null)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/addresses.php', ['user' => $user]
        );
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/2fa')]
    public function add2FA(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->userService->getUserForProfile($context->id);
        if ($user === null)
            throw new NotFoundException();

        $otp = $this->authService->generateTotp($user);

        echo $this->render('webstore/account/add_otp.php',
            ['user' => $user, 'otp' => $otp]
        );
    }

}
